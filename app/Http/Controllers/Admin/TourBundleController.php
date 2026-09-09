<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use App\Models\TourBundle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TourBundleController extends Controller
{
    public function index()
    {
        $bundles = TourBundle::with('destinations')->get();
        $destinations = Destination::all();

        return view('admin.bundle.dashboard', compact('bundles', 'destinations'));
    }

    public function store(Request $request)
    {
        if ($request->has('destination_ids')) {
            $request->merge([
                'destination_ids' => array_values(array_filter($request->input('destination_ids')))
            ]);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'slot' => 'nullable|integer|min:1',
            'transportation_id' => 'nullable|exists:transportations,id',
            'destination_ids' => 'required|array|min:1',
            'destination_ids.*' => 'exists:destinations,id',
        ]);

        DB::transaction(function () use ($validated) {
            $bundle = TourBundle::create([
                'admin_id' => auth()->id(),
                'transportation_id' => $validated['transportation_id'] ?? null,
                'name' => $validated['name'],
                'description' => $validated['description'],
                'price' => $validated['price'],
                'slot' => $validated['slot'] ?? null,
            ]);

            $bundle->destinations()->attach(array_unique($validated['destination_ids']));
        });

        return back()->with('message', 'Tour bundle created successfully');
    }

    public function update(Request $request, $id)
    {
        $bundle = TourBundle::findOrFail($id);

        if ($request->has('destination_ids')) {
            $request->merge([
                'destination_ids' => array_values(array_filter($request->input('destination_ids')))
            ]);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'slot' => 'nullable|integer|min:1',
            'transportation_id' => 'nullable|exists:transportations,id',
            'destination_ids' => 'required|array|min:1',
            'destination_ids.*' => 'exists:destinations,id',
        ]);

        DB::transaction(function () use ($bundle, $validated) {
            $bundle->update([
                'transportation_id' => $validated['transportation_id'] ?? null,
                'name' => $validated['name'],
                'description' => $validated['description'],
                'price' => $validated['price'],
                'slot' => $validated['slot'] ?? null,
            ]);

            $bundle->destinations()->sync(array_unique($validated['destination_ids']));
        });

        return back()->with('message', 'Tour bundle updated successfully');
    }

    public function destroy($id)
    {
        $bundle = TourBundle::findOrFail($id);

        DB::transaction(function () use ($bundle) {
            $bundle->destinations()->detach();
            $bundle->delete();
        });

        return back()->with('message', 'Tour bundle deleted successfully');
    }
}