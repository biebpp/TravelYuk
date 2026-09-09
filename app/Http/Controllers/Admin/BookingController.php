<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\TourBundle;
use DB;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index()
    {
        $bundles = TourBundle::with('destinations')->get();
        $bookings = Booking::with(['bundle', 'user'])->get();
        
        return view("admin.booking.dashboard", compact('bookings', 'bundles'));
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        $request->validate([
            'status' => 'required|in:accepted,declined',
        ]);

        $booking->update([
            'status' => $request->status,
        ]);

        return back()->with('message', "Booking status updated to {$request->status}.");
    }
}
