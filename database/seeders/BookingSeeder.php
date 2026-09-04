<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class BookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table("bookings")->insert([
            'name' => 'Test Booking',
            'user_id' => 1,
            'transaction_id' => null,
            'bundle_id' => null,
            'date' => now(),
            'status' => 'pending',
        ]);
    }
}
