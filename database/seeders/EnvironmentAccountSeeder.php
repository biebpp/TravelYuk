<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use RuntimeException;

abstract class EnvironmentAccountSeeder extends Seeder
{
    protected function seedConfiguredAccount(string $account, string $role): void
    {
        $email = config("seeding.{$account}.email");
        $password = config("seeding.{$account}.password");

        if (($email === null || trim($email) === '') && ($password === null || $password === '')) {
            if (app()->environment('production')) {
                throw new RuntimeException("Seed credentials for the {$role} account are required in production.");
            }

            $this->command?->warn("The {$role} seed account was skipped because its credentials are not configured.");

            return;
        }

        if (! is_string($email) || trim($email) === '' || ! is_string($password) || $password === '') {
            throw new RuntimeException("Both email and password must be configured for the {$role} seed account.");
        }

        $email = trim($email);
        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new RuntimeException("The configured email for the {$role} seed account is invalid.");
        }

        $user = User::firstOrCreate(
            ['email' => $email],
            [
                'name' => $role,
                'password' => Hash::make($password),
                'role' => $role,
            ],
        );

        if ($user->role !== $role) {
            throw new RuntimeException("The configured email for the {$role} seed account belongs to another role.");
        }
    }
}
