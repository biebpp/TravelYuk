<?php

namespace App\Http\Controllers;

use App\Services\NominatimService;
use Illuminate\Http\Request;

class GeocodingController extends Controller
{
    protected $nominatimService;

    public function __construct(NominatimService $nominatimService)
    {
        $this->nominatimService = $nominatimService;
    }

    public function getCoordinates(Request $request)
    {
        $request->validate([
            'address' => 'required|string'
        ]);

        $address = $request->input('address');
        $coordinates = $this->nominatimService->getCoordinates($address);

        if ($coordinates === null) {
            return response()->json(['message' => 'Address not found'], 404);
        }

        return response()->json($coordinates);
    }
}