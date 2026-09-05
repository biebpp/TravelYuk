<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Auth;
use DB;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index() {
        $bookings = DB::table('bookings')
            ->join('users', 'bookings.user_id', '=', 'users.id')
            ->select('bookings.*')
            ->where('bookings.user_id', Auth::id())
            ->get();
        return view("client.booking", [
            "bookings" => $bookings,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'status' => ['nullable', 'string', 'in:pending,accepted,declined'],
        ]);

        Booking::create([
            'name' =>  $request->name,
            'status' => 'pending',
            'user_id' => Auth::id(),
        ]);
        
        return back()->with('message', 'Booking created successfully');
    }
}
