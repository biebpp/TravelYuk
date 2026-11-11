<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\TourBundle;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function index(TourBundle $bundle)
    {
        $bundle->load(['destinations', 'tickets.destination']);
        abort_unless($bundle->is_active, 404);
        abort_if($bundle->slot !== null && $bundle->slot < 1, 404);

        return view('pages.packages.booking', [
            'bundle' => $bundle,
            'bookingToken' => (string) Str::uuid(),
        ]);
    }

    public function beginBooking(TourBundle $bundle)
    {
        abort_unless($bundle->is_active, 404);
        abort_if($bundle->slot !== null && $bundle->slot < 1, 404);

        return redirect()->route('payment', $bundle);
    }

    public function payment(Booking $booking)
    {
        abort_unless($booking->user_id === auth()->id(), 404);
        return redirect()->route('client.booking')->with('message', "Pembayaran tidak dilakukan secara online. Booking {$booking->booking_code} menunggu konfirmasi admin.");
    }
}
