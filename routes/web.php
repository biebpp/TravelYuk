<?php

use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Admin\DestinationController;
use App\Http\Controllers\Admin\TourBundleController;
use App\Http\Controllers\Admin\TicketController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Client\BookingController as ClientBookingController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Client\PaymentController;
use App\Http\Controllers\GeocodingController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ClientBookingController::class, 'home'])->name('index');
Route::get('/packages', [ClientBookingController::class, 'packagesIndex'])->name('packages');
Route::get('/packages/{bundle}', [PaymentController::class, 'index'])->name('payment');
Route::get('/packages/{bundle}/booking', [PaymentController::class, 'beginBooking'])
    ->middleware(['auth', 'role:client'])
    ->name('packages.booking');
Route::get('/packages/{booking}/payment', [PaymentController::class, 'payment'])->middleware(['auth'])->name('payment.packages');

Route::post('/get-coordinates', [GeocodingController::class, 'getCoordinates']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->get('/dashboard', function () {
    return match (auth()->user()->role) {
        'superadmin' => redirect()->route('admin.users.dashboard'),
        'admin' => redirect()->route('admin.dashboard'),
        default => redirect()->route('client.dashboard'),
    };
})->name('dashboard');

Route::middleware(['auth'])->get('/booking', function () {
    return in_array(auth()->user()->role, ['admin', 'superadmin'], true)
        ? redirect()->route('admin.booking')
        : redirect()->route('client.booking');
})->name('booking');

Route::middleware(['role:superadmin'])->prefix('admin')->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('admin.users.dashboard');
    Route::post('/users', [UserController::class, 'store'])->name('admin.users.store');
    Route::patch('/users/{user}', [UserController::class, 'update'])->name('admin.users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');
});

Route::middleware(['role:admin,superadmin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/booking', [AdminBookingController::class, 'index'])->name('admin.booking');

    Route::get('/destination', [DestinationController::class, 'index'])->name('admin.destinations');
    Route::post('/destination', [DestinationController::class, 'store'])->name('admin.destinations.store');
    Route::patch('/destination/{destination}', [DestinationController::class, 'update'])->name('admin.destinations.update');
    Route::delete('/destination/{destination}', [DestinationController::class, 'destroy'])->name('admin.destinations.destroy');

    Route::get('/tickets', [TicketController::class, 'index'])->name('admin.tickets');
    Route::post('/tickets', [TicketController::class, 'store'])->name('admin.tickets.store');
    Route::patch('/tickets/{ticket}', [TicketController::class, 'update'])->name('admin.tickets.update');
    Route::delete('/tickets/{ticket}', [TicketController::class, 'destroy'])->name('admin.tickets.destroy');

    Route::get('/bundles', [TourBundleController::class, 'index'])->name('admin.bundles');
    Route::post('/bundles', [TourBundleController::class, 'store'])->name('admin.bundles.store');
    Route::patch('/bundles/{bundle}', [TourBundleController::class, 'update'])->name('admin.bundles.update');
    Route::delete('/bundles/{bundle}', [TourBundleController::class, 'destroy'])->name('admin.bundles.destroy');

    Route::get('/transaction', [TransactionController::class, 'index'])->name('admin.transactions');
});

Route::middleware(['role:client'])->prefix('client')->group(function () {
    Route::get('/dashboard', [ClientBookingController::class, 'dashboard'])->name('client.dashboard');

    Route::get('/booking', [ClientBookingController::class, 'index'])->name('client.booking');
    Route::post('/booking', [ClientBookingController::class, 'store'])->name('client.booking.store');
    Route::delete('/booking/{id}', [ClientBookingController::class, 'destroy'])->name('client.booking.destroy');
});

require __DIR__ . '/auth.php';
