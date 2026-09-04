<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use DB;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = DB::table('bookings')
            ->join('users', 'bookings.user_id', '=', 'users.id')
            ->select('bookings.*', 'users.name as user_name')
            ->get();
        return view("admin.booking.dashboard", [
            "bookings" => $bookings,
        ]);
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
