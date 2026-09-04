<?php

use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('index');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->get('/dashboard', function () {
    return auth()->user()->role === 'admin'
        ? redirect()->route('admin.users.dashboard')
        : redirect()->route('client.dashboard');
})->name('dashboard');
Route::middleware(['auth'])->get('/booking', function () {
    return auth()->user()->role === 'admin'
        ? redirect()->route('admin.booking')
        : redirect()->route('client.booking');
})->name('booking');

Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [UserController::class, 'index'])->name('admin.users.dashboard');
    Route::post('/dashboard', [UserController::class, 'store'])->name('admin.users.store');
    Route::patch('/dashboard/{id}', [UserController::class, 'update'])->name('admin.users.update');
    Route::delete('/dashboard/{id}', [UserController::class, 'destroy'])->name('admin.users.destroy');
    
    Route::get('/booking', [BookingController::class, 'index'])->name('admin.booking');
});


Route::get('/client/dashboard', function () {
    return view('client.dashboard');
})->middleware('role:client')->name('client.dashboard');

Route::get('/client/booking', function () {
    return view('client.booking');
})->middleware('role:client')->name('client.booking');

require __DIR__.'/auth.php';
