<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use App\Models\Ticket;
use App\Models\TourBundle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class TourBundleController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
        ]);

        $bundles = TourBundle::with(['destinations', 'tickets.destination', 'bookings'])
            ->when($filters['q'] ?? null, function ($query, string $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhereHas('destinations', fn ($query) => $query->where('name', 'like', "%{$search}%"));
                });
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();
        $destinations = Destination::orderBy('name')->get();
        $tickets = Ticket::with('destination')->orderBy('name')->get();

        return view('admin.bundle.dashboard', compact('bundles', 'destinations', 'tickets', 'filters'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'slot' => 'nullable|integer|min:0',
            'transportation_id' => 'nullable|exists:transportations,id',
            'destination_ids' => 'required|array|min:1',
            'destination_ids.*' => 'integer|distinct|exists:destinations,id',
            'ticket_ids' => 'nullable|array',
            'ticket_ids.*' => 'integer|distinct|exists:tickets,id',
            'duration_days' => ['required', 'integer', 'min:1', 'max:365'],
            'duration_nights' => ['required', 'integer', 'min:0', 'max:364'],
            'facilities' => ['nullable', 'string', 'max:5000'],
            'departure_date' => ['nullable', 'date', 'after_or_equal:today'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['destination_ids'] = array_unique($validated['destination_ids']);
        $validated['ticket_ids'] = $validated['ticket_ids'] ?? [];
        $this->ensureTicketsBelongToDestinations($validated['ticket_ids'], $validated['destination_ids']);
        $imagePath = $this->storeImage($request);

        DB::transaction(function () use ($validated, $imagePath, $request) {
            $bundle = TourBundle::create([
                'admin_id' => auth()->id(),
                'transportation_id' => $validated['transportation_id'] ?? null,
                'name' => $validated['name'],
                'description' => $validated['description'],
                'price' => $validated['price'],
                'slot' => $validated['slot'] ?? null,
                'image_path' => $imagePath,
                'duration_days' => $validated['duration_days'],
                'duration_nights' => $validated['duration_nights'],
                'facilities' => $this->parseFacilities($validated['facilities'] ?? ''),
                'departure_date' => $validated['departure_date'] ?? null,
                'is_active' => $request->boolean('is_active'),
            ]);

            $bundle->destinations()->attach(array_unique($validated['destination_ids']));
            $bundle->tickets()->attach($validated['ticket_ids']);
        });

        return back()->with('message', 'Tour bundle created successfully');
    }

    public function update(Request $request, $id)
    {
        $bundle = TourBundle::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'slot' => 'nullable|integer|min:0',
            'transportation_id' => 'nullable|exists:transportations,id',
            'destination_ids' => 'required|array|min:1',
            'destination_ids.*' => 'integer|distinct|exists:destinations,id',
            'ticket_ids' => 'nullable|array',
            'ticket_ids.*' => 'integer|distinct|exists:tickets,id',
            'duration_days' => ['required', 'integer', 'min:1', 'max:365'],
            'duration_nights' => ['required', 'integer', 'min:0', 'max:364'],
            'facilities' => ['nullable', 'string', 'max:5000'],
            'departure_date' => ['nullable', 'date'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['destination_ids'] = array_unique($validated['destination_ids']);
        $validated['ticket_ids'] = $validated['ticket_ids'] ?? [];
        $this->ensureTicketsBelongToDestinations($validated['ticket_ids'], $validated['destination_ids']);
        $reservedParticipants = $bundle->bookings()->where('inventory_reserved', true)->sum('participants');
        if ($validated['slot'] !== null && $validated['slot'] < $reservedParticipants) {
            throw ValidationException::withMessages([
                'slot' => 'Kuota baru tidak boleh lebih kecil dari peserta yang sudah memiliki booking aktif.',
            ]);
        }

        $oldImagePath = $bundle->image_path;
        $newImagePath = $this->storeImage($request);

        DB::transaction(function () use ($bundle, $validated, $newImagePath, $oldImagePath, $request) {
            $bundle->update([
                'transportation_id' => $validated['transportation_id'] ?? null,
                'name' => $validated['name'],
                'description' => $validated['description'],
                'price' => $validated['price'],
                'slot' => $validated['slot'] ?? null,
                'image_path' => $newImagePath ?? $oldImagePath,
                'duration_days' => $validated['duration_days'],
                'duration_nights' => $validated['duration_nights'],
                'facilities' => $this->parseFacilities($validated['facilities'] ?? ''),
                'departure_date' => $validated['departure_date'] ?? null,
                'is_active' => $request->boolean('is_active'),
            ]);

            $bundle->destinations()->sync(array_unique($validated['destination_ids']));
            $bundle->tickets()->sync($validated['ticket_ids']);
        });

        if ($newImagePath && $oldImagePath) {
            $this->deleteImage($oldImagePath);
        }

        return back()->with('message', 'Tour bundle updated successfully');
    }

    public function destroy($id)
    {
        $bundle = TourBundle::findOrFail($id);

        if ($bundle->bookings()->exists()) {
            return back()->withErrors([
                'bundle' => 'Paket memiliki riwayat booking dan tidak dapat dihapus. Nonaktifkan paket agar riwayat tetap aman.',
            ]);
        }

        $imagePath = $bundle->image_path;
        DB::transaction(function () use ($bundle) {
            $bundle->destinations()->detach();
            $bundle->tickets()->detach();
            $bundle->delete();
        });

        if ($imagePath) {
            $this->deleteImage($imagePath);
        }

        return back()->with('message', 'Tour bundle deleted successfully');
    }

    private function ensureTicketsBelongToDestinations(array $ticketIds, array $destinationIds): void
    {
        $ticketMatches = Ticket::query()
            ->whereIn('id', $ticketIds)
            ->whereIn('destination_id', $destinationIds)
            ->where('is_active', true)
            ->count();

        if ($ticketMatches !== count($ticketIds)) {
            throw ValidationException::withMessages([
                'ticket_ids' => 'Setiap tiket paket harus berasal dari destinasi yang dipilih.',
            ]);
        }
    }

    private function parseFacilities(string $facilities): array
    {
        return array_values(array_unique(array_filter(array_map(
            static fn (string $facility): string => trim($facility),
            preg_split('/\r\n|\r|\n/', $facilities) ?: [],
        ))));
    }

    private function storeImage(Request $request): ?string
    {
        if (! $request->hasFile('image')) {
            return null;
        }

        $path = $request->file('image')->store('packages', 'public');
        if (! $path) {
            abort(500, 'Gambar paket gagal disimpan.');
        }

        return $path;
    }

    private function deleteImage(string $path): void
    {
        if (! Storage::disk('public')->delete($path)) {
            Log::warning('Unable to delete an old package image.', ['path' => $path]);
        }
    }
}
