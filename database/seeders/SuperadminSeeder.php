<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SuperadminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // DB::table("users")->delete(
        //     DB::table("users")->where('name', 'admin')->pluck('id')
        // );

        DB::table("users")->insert([
            'name' => 'superadmin',
            'email' => 'superadmin@admin.com',
            'password' => Hash::make("admin"),
            'role' => 'superadmin',
        ]);
    }
}
