<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'destination_id' => ['nullable', 'integer', 'exists:destinations,id'],
        ]);

        return view('admin.ticket.dashboard', [
            'tickets' => Ticket::with('destination')
                ->when($filters['q'] ?? null, function ($query, string $search) {
                    $query->where(function ($query) use ($search) {
                        $query->where('name', 'like', "%{$search}%")
                            ->orWhereHas('destination', fn ($query) => $query->where('name', 'like', "%{$search}%"));
                    });
                })
                ->when($filters['destination_id'] ?? null, fn ($query, int $destinationId) => $query->where('destination_id', $destinationId))
                ->latest()
                ->paginate(15)
                ->withQueryString(),
            'destinations' => Destination::orderBy('name')->get(),
            'filters' => $filters,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validateTicket($request);
        $validated['is_active'] = $request->boolean('is_active');

        Ticket::create($validated);

        return back()->with('message', 'Tiket berhasil ditambahkan.');
    }

    public function update(Request $request, Ticket $ticket)
    {
        $validated = $this->validateTicket($request);
        $validated['is_active'] = $request->boolean('is_active');

        $ticket->update($validated);

        return back()->with('message', 'Tiket berhasil diperbarui.');
    }

    public function destroy(Ticket $ticket)
    {
        if ($ticket->bookingItems()->whereHas('booking', fn ($query) => $query->where('inventory_reserved', true))->exists()) {
            return back()->withErrors([
                'ticket' => 'Tiket masih digunakan oleh booking aktif dan tidak dapat dihapus.',
            ]);
        }

        $ticket->delete();

        return back()->with('message', 'Tiket berhasil dihapus.');
    }

    private function validateTicket(Request $request): array
    {
        return $request->validate([
            'destination_id' => ['required', 'integer', 'exists:destinations,id'],
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:10000'],
            'price' => ['required', 'numeric', 'min:0', 'max:9999999999.99'],
            'quota' => ['nullable', 'integer', 'min:0'],
            'valid_from' => ['nullable', 'date'],
            'valid_until' => ['nullable', 'date', 'after_or_equal:valid_from'],
            'is_active' => ['nullable', 'boolean'],
        ]);
    }
}
