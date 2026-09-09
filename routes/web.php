<?php

use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Admin\BundleController;
use App\Http\Controllers\Admin\DestinationController;
use App\Http\Controllers\Admin\TourBundleController;
use App\Http\Controllers\Client\BookingController as ClientBookingController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\GeocodingController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpKernel\Bundle\Bundle;

Route::get('/', function () {
    return view('welcome');
})->name('index');
Route::get('/packages', [ClientBookingController::class, 'packagesIndex'])->name('packages');

Route::post('/get-coordinates', [GeocodingController::class, 'getCoordinates']);

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

Route::middleware(['role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [UserController::class, 'index'])->name('admin.users.dashboard');
    Route::post('/dashboard', [UserController::class, 'store'])->name('admin.users.store');
    Route::patch('/dashboard/{id}', [UserController::class, 'update'])->name('admin.users.update');
    Route::delete('/dashboard/{id}', [UserController::class, 'destroy'])->name('admin.users.destroy');

    Route::get('/booking', [AdminBookingController::class, 'index'])->name('admin.booking');
    Route::patch('/booking/{booking}', [AdminBookingController::class, 'updateStatus'])->name('admin.booking.status');

    Route::get('/destination', [DestinationController::class, 'index'])->name('admin.destinations');
    Route::post('/destination', [DestinationController::class, 'store'])->name('admin.destinations.store');
    Route::patch('/destination/{id}', [DestinationController::class, 'update'])->name('admin.destinations.update');
    Route::delete('/destination/{id}', [DestinationController::class, 'destroy'])->name('admin.destinations.destroy');

    Route::get('/bundles', [TourBundleController::class, 'index'])->name('admin.bundles');
    Route::post('/bundles', [TourBundleController::class, 'store'])->name('admin.bundles.store');
    Route::patch('/bundles/{id}', [TourBundleController::class, 'update'])->name('admin.bundles.update');
    Route::delete('/bundles/{id}', [TourBundleController::class, 'destroy'])->name('admin.bundles.destroy');
});

Route::middleware(['role:client'])->prefix('client')->group(function () {
    Route::get('/dashboard', function () {
        return view('client.dashboard');
    })->name('client.dashboard');

    Route::get('/booking', [ClientBookingController::class, 'index'])->name('client.booking');
    Route::post('/booking', [ClientBookingController::class, 'store'])->name('client.booking.store');
    Route::patch('/booking/{booking}/status', [ClientBookingController::class, 'updateStatus'])->name('client.booking.status');
    Route::delete('/booking/{id}', [ClientBookingController::class, 'destroy'])->name('client.booking.destroy');
});

require __DIR__ . '/auth.php';
