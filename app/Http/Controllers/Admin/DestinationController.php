<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use Illuminate\Http\Request;

class DestinationController extends Controller
{
    public function index() {
        $destinations = Destination::all();

        return view("admin.destination.dashboard", [
            "destinations" => $destinations,
        ]);
    }

    public function store(Request $request) {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string'],
            'description' => ['nullable', 'string'],
        ]);

        Destination::create([
            'name' =>  $request->name,
            'address' => $request->address,
            'description' => $request->description,
        ]);
        
        return back()->with('message', 'Destination created successfully');
    }

    public function update(Request $request, $id)
    {
        $destination = Destination::findOrFail($id);
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'address' => ['required', 'string'],
            'description' => ['nullable', 'string'],
        ]);
        $destination->update($validatedData);
        return back()->with('message', 'Destination updated successfully');
    }


    public function destroy(Destination $destination, $id)
    {
        $destination = Destination::findOrFail($id);
        $destination->delete();

        return back()->with('message', 'Destination deleted successfully');
    }
}
