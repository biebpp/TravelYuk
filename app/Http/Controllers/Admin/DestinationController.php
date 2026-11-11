<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DestinationController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'category' => ['nullable', 'string', 'max:100'],
        ]);

        $destinations = Destination::query()
            ->when($filters['q'] ?? null, function ($query, string $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('address', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->when($filters['category'] ?? null, fn ($query, string $category) => $query->where('category', $category))
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        $categories = Destination::query()
            ->whereNotNull('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        return view('admin.destination.dashboard', compact('destinations', 'categories', 'filters'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:100'],
            'address' => ['required', 'string', 'max:1000'],
            'description' => ['nullable', 'string', 'max:10000'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'is_featured' => ['nullable', 'boolean'],
        ]);

        $imagePath = $this->storeImage($request);
        Destination::create([
            ...$validated,
            'image_path' => $imagePath,
            'is_featured' => $request->boolean('is_featured'),
        ]);

        return back()->with('message', 'Destinasi berhasil ditambahkan.');
    }

    public function update(Request $request, Destination $destination)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:100'],
            'address' => ['required', 'string', 'max:1000'],
            'description' => ['nullable', 'string', 'max:10000'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'is_featured' => ['nullable', 'boolean'],
        ]);

        $oldImagePath = $destination->image_path;
        $newImagePath = $this->storeImage($request);

        $destination->update([
            ...$validated,
            'image_path' => $newImagePath ?? $oldImagePath,
            'is_featured' => $request->boolean('is_featured'),
        ]);

        if ($newImagePath && $oldImagePath) {
            $this->deleteImage($oldImagePath);
        }

        return back()->with('message', 'Destinasi berhasil diperbarui.');
    }

    public function destroy(Destination $destination)
    {
        if ($destination->tourBundles()->exists() || $destination->tickets()->whereHas('tourBundles')->exists()) {
            return back()->withErrors([
                'destination' => 'Lepaskan destinasi ini dari paket wisata dan tiket terkait sebelum menghapusnya.',
            ]);
        }

        $imagePath = $destination->image_path;
        $destination->delete();

        if ($imagePath) {
            $this->deleteImage($imagePath);
        }

        return back()->with('message', 'Destinasi berhasil dihapus.');
    }

    private function storeImage(Request $request): ?string
    {
        if (! $request->hasFile('image')) {
            return null;
        }

        $path = $request->file('image')->store('destinations', 'public');
        if (! $path) {
            abort(500, 'Gambar destinasi gagal disimpan.');
        }

        return $path;
    }

    private function deleteImage(string $path): void
    {
        if (! Storage::disk('public')->delete($path)) {
            Log::warning('Unable to delete an old destination image.', ['path' => $path]);
        }
    }
}
