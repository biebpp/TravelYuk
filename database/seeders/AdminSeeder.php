<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->where('email', 'admin@admin.com')->delete();

        DB::table("users")->insert([
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make("admin"),
            'role' => 'admin',
        ]);
    }
}
