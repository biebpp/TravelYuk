<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\TourBundle;
use Auth;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(TourBundle $bundle)
    {
        return view("pages.packages.booking", compact('bundle'));
    }
}
