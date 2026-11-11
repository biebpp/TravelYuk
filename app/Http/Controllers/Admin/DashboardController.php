<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Destination;
use App\Models\Ticket;
use App\Models\TourBundle;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'destinationCount' => Destination::count(),
            'ticketCount' => Ticket::count(),
            'bundleCount' => TourBundle::count(),
            'availableBundleCount' => TourBundle::where('is_active', true)
                ->where(fn ($query) => $query->whereNull('slot')->orWhere('slot', '>', 0))
                ->count(),
            'bookingCount' => Booking::count(),
            'pendingBookingCount' => Booking::where('status', 'pending')->count(),
            'recentBookings' => Booking::with(['user', 'bundle'])
                ->latest()
                ->limit(8)
                ->get(),
        ]);
    }
}
