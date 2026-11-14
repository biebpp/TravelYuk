<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use RuntimeException;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $accounts = [
            config('seeding.admin.email'),
            config('seeding.admin.password'),
            config('seeding.superadmin.email'),
            config('seeding.superadmin.password'),
        ];
        $configured = array_values(array_filter(
            $accounts,
            fn ($value) => is_string($value) && trim($value) !== '',
        ));

        if ($configured === []) {
            if (app()->environment('production')) {
                throw new RuntimeException('Privileged seed accounts must be configured in production.');
            }

            $this->command?->warn('Privileged seed accounts were skipped because no seed credentials are configured.');

            return;
        }

        if (count($configured) !== count($accounts)) {
            throw new RuntimeException('All four privileged seed account values must be configured together.');
        }

        $adminEmail = trim($accounts[0]);
        $superadminEmail = trim($accounts[2]);

        if (! filter_var($adminEmail, FILTER_VALIDATE_EMAIL)
            || ! filter_var($superadminEmail, FILTER_VALIDATE_EMAIL)) {
            throw new RuntimeException('Privileged seed account emails must be valid email addresses.');
        }

        if (strcasecmp($adminEmail, $superadminEmail) === 0) {
            throw new RuntimeException('Admin and superadmin seed account emails must be different.');
        }

        $this->call([
            AdminSeeder::class,
            SuperadminSeeder::class,
        ]);
    }
}
