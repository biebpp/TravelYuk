<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Services\BookingInventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class BookingController extends Controller
{
    public function __construct(private readonly BookingInventory $inventory)
    {
    }

    public function index(Request $request)
    {
        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', 'in:pending,accepted,declined,cancelled,payment'],
            'payment_status' => ['nullable', 'in:unpaid,paid'],
        ]);

        $bookings = Booking::with(['bundle', 'user', 'items'])
            ->when($filters['q'] ?? null, function ($query, string $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('booking_code', 'like', "%{$search}%")
                        ->orWhere('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhereHas('bundle', fn ($query) => $query->where('name', 'like', "%{$search}%"));
                });
            })
            ->when($filters['status'] ?? null, fn ($query, string $status) => $query->where('status', $status))
            ->when($filters['payment_status'] ?? null, fn ($query, string $status) => $query->where('payment_status', $status))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.booking.dashboard', compact('bookings', 'filters'));
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:pending,accepted,declined'],
            'payment_status' => ['required', 'in:unpaid,paid'],
        ]);

        DB::transaction(function () use ($booking, $validated) {
            $booking = Booking::query()->lockForUpdate()->findOrFail($booking->id);

            if (in_array($booking->status, ['declined', 'cancelled'], true)
                && $validated['status'] !== $booking->status) {
                throw ValidationException::withMessages([
                    'status' => 'Booking yang telah dibatalkan atau ditolak tidak dapat diaktifkan kembali.',
                ]);
            }

            if (in_array($booking->status, ['declined', 'cancelled'], true)
                && $validated['payment_status'] === 'paid') {
                throw ValidationException::withMessages([
                    'payment_status' => 'Booking yang dibatalkan atau ditolak tidak dapat ditandai lunas.',
                ]);
            }

            if ($validated['status'] === 'declined') {
                $this->inventory->release($booking);
            }

            $booking->update($validated);
        });

        return back()->with('message', 'Status booking berhasil diperbarui.');
    }
}
