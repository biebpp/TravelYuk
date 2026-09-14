<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\TourBundle;
use App\Models\Transaction;
use Auth;
use DB;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function callDatabase()
    {
        $bundles = TourBundle::with('destinations')->get();
        $bookings = Booking::with('bundle.destinations')
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
            'bundle_id' => ['required', 'integer', 'exists:tour_bundles,id'],
            'status' => ['nullable', 'string', 'in:payment,pending,accepted,declined'],
        ]);

        $booking = Booking::create([
            'name' => Auth::user()->name . ' is Booking For ' . $request->bundle_name,
            'bundle_id' => $request->bundle_id,
            'status' => 'payment',
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('payment.packages', ['booking' => $booking->id])->with('message', 'Booking created successfully!');
    }

    public function updateStatus(Request $request, Booking $booking, TourBundle $bundle)
    {
        $request->validate([
            'status' => 'required|in:payment,pending',
            'bundle_id' => 'required|exists:tour_bundles,id',
            'transaction_id' => 'required',
            'payment_method' => 'required',
            'price' => 'required',
        ]);

        Transaction::create([
            'id' => $request->transaction_id,
            'user_id' => Auth::id(),
            'payment' => $request->payment_method,
            'nominal' => $request->price,
            'transaction_date' => date('Ymd'),
            'status' => 'success',
        ]);

        $bundle = TourBundle::findOrFail($request->bundle_id);
        if ($booking->status !== 'pending' && $request->status === 'pending') {
            $bundle->decrement('slot');
        }

        $booking->update([
            'status' => $request->status,
            'transaction_id' => $request->transaction_id,
        ]);


        return redirect()->route('client.booking')->with('message', "Booking status updated to {$request->status}.");
    }

    public function destroy(Booking $booking, $id)
    {
        $booking = Booking::where('user_id', Auth::id())->findOrFail($id);
        $booking->delete();

        return back()->with('message', 'Booking deleted successfully');
    }
}
