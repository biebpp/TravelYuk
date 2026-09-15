<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with('user.bookings.bundle')->latest()->get();

        $transactions->transform(function ($transaction) {
        if ($transaction->user && $transaction->user->bookings->isNotEmpty()) {
            
            $matchedBooking = $transaction->user->bookings->firstWhere('transaction_id', $transaction->id);

            if (!$matchedBooking) {
                $matchedBooking = $transaction->user->bookings
                    ->where('user_id', $transaction->user_id)
                    ->sortBy(function ($booking) use ($transaction) {
                        return abs(strtotime($booking->created_at) - strtotime($transaction->created_at));
                    })
                    ->first();
            }

            $transaction->bundle_name = $matchedBooking?->bundle?->name ?? 'N/A';
        } else {
            $transaction->bundle_name = 'N/A';
        }

        return $transaction;
    });

        $users = User::all();
        $total = $transactions->sum('nominal');
        $outcome = 123456;
        $profit = $transactions->sum('nominal') - $outcome;
        
        return view("admin.transaction.dashboard", compact('transactions', 'users', 'total', 'outcome', 'profit'));
    }
}
