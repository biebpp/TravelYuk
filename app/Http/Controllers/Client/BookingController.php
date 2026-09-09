<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\TourBundle;
use Auth;
use DB;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function callDatabase() {
        $bundles = TourBundle::with('destinations')->get();
        $bookings = Booking::with('bundle')
            ->where('user_id', Auth::id())
            ->get();
        return [$bundles, $bookings];
    }
    

    public function index()
    {
        [$bundles, $bookings] = $this->callDatabase();
        return view("client.booking", compact('bookings', 'bundles'));
    }

    public function packagesIndex()
    {
        [$bundles] = $this->callDatabase();
        return view("pages.packages.index", compact('bundles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'bundle_id' => ['required', 'integer', 'max:255'],
            'status' => ['nullable', 'string', 'in:payment,pending,accepted,declined'],
        ]);

        Booking::create([
            'name' => $request->name,
            'bundle_id' => $request->bundle_id,
            'status' => 'payment',
            'user_id' => Auth::id(),
        ]);

        return back()->with('message', 'Booking created successfully');
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        $request->validate([
            'status' => 'required|in:payment,pending',
        ]);

        $booking->update([
            'status' => $request->status,
        ]);

        return back()->with('message', "Booking status updated to {$request->status}.");
    }

    public function destroy(Booking $booking, $id)
    {
        $booking = Booking::where('user_id', Auth::id())->findOrFail($id);
        $booking->delete();

        return back()->with('message', 'Booking deleted successfully');
    }
}
