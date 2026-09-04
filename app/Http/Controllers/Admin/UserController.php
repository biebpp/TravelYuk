<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        // $users = User::where('role', 'client')->get();    
        $users = User::all();

        return view("admin.user.dashboard", [
            "users" => $users,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'. User::class],
            'password' => ['required', Rules\Password::defaults()],
            'role' => ['required', 'in:admin,client'],
        ]);

        User::create([
            'name' =>  $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'password' => Hash::make($request->password),
        ]);
        
        return back()->with('message', 'User created successfully');
    }

    public function update(Request $request, User $users, $id)
    {
        $user = User::findOrFail($id);
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role' => 'required|in:admin,client',
            'password' => 'nullable|string|min:8',
        ]);
        if (empty($validatedData['password'])) {
            unset($validatedData['password']);
        } else {
            $validatedData['password'] = Hash::make($validatedData['password']);
        }
        $user->update($validatedData);
        return back()->with('message', 'User updated successfully');
    }

    public function destroy(User $users, $id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return back()->with('message', 'User deleted successfully');
    }
}
