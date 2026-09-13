<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\TourBundle;
use Auth;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(TourBundle $bundle, Booking $booking)
    {
        return view("pages.packages.booking", compact('bundle', 'booking'));
    }

    public function payment(TourBundle $bundle, Booking $booking)
    {
        return view("pages.packages.payment", compact('bundle', 'booking'));
    }
}
