<?php

namespace Database\Seeders;

use App\Models\User;
use DB;
use Hash;
use Illuminate\Database\Seeder;

class ExampleSeeder extends Seeder
{

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        DB::table("users")->insert([
            'username' => 'user',
            'name' => 'Test User',
            'password' => Hash::make("admin"),
            'contact' => '1234567890',
            'email' => 'test@example.com',
            'birth_date' => '2022-02-02',
            'gender' => 'test',
            'native' => 'test',
            'profile' => '',
        ]);
    }
}
