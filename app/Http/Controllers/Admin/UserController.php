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
        $users = User::query()->latest()->paginate(20);

        return view('admin.user.dashboard', compact('users'));
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

    public function update(Request $request, User $user)
    {
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

    public function destroy(User $user)
    {
        if ($user->is(auth()->user())) {
            return back()->withErrors(['user' => 'Anda tidak dapat menghapus akun yang sedang digunakan.']);
        }

        if ($user->bookings()->exists()) {
            return back()->withErrors(['user' => 'Pengguna memiliki riwayat booking dan tidak dapat dihapus.']);
        }

        $user->delete();

        return back()->with('message', 'User deleted successfully');
    }
}
