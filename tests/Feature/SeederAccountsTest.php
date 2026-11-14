<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use RuntimeException;
use Tests\TestCase;

class SeederAccountsTest extends TestCase
{
    use RefreshDatabase;

    public function test_default_seeder_skips_privileged_accounts_when_unconfigured_outside_production(): void
    {
        config([
            'seeding.admin.email' => null,
            'seeding.admin.password' => null,
            'seeding.superadmin.email' => null,
            'seeding.superadmin.password' => null,
        ]);

        $this->seed(DatabaseSeeder::class);

        $this->assertDatabaseCount('users', 0);
    }

    public function test_default_seeder_creates_accounts_idempotently_without_resetting_passwords(): void
    {
        config([
            'seeding.admin.email' => 'seed-admin@example.com',
            'seeding.admin.password' => 'test-admin-password',
            'seeding.superadmin.email' => 'seed-superadmin@example.com',
            'seeding.superadmin.password' => 'test-superadmin-password',
        ]);

        $this->seed(DatabaseSeeder::class);

        $admin = User::where('email', 'seed-admin@example.com')->firstOrFail();
        $superadmin = User::where('email', 'seed-superadmin@example.com')->firstOrFail();

        $this->assertSame('admin', $admin->role);
        $this->assertSame('superadmin', $superadmin->role);
        $this->assertTrue(Hash::check('test-admin-password', $admin->password));
        $this->assertTrue(Hash::check('test-superadmin-password', $superadmin->password));

        $admin->update(['password' => Hash::make('manually-rotated-password')]);
        $this->seed(DatabaseSeeder::class);

        $this->assertDatabaseCount('users', 2);
        $this->assertTrue(Hash::check('manually-rotated-password', $admin->refresh()->password));
    }

    public function test_default_seeder_rejects_partial_configuration_before_creating_accounts(): void
    {
        config([
            'seeding.admin.email' => 'seed-admin@example.com',
            'seeding.admin.password' => 'test-admin-password',
            'seeding.superadmin.email' => null,
            'seeding.superadmin.password' => null,
        ]);

        try {
            $this->seed(DatabaseSeeder::class);
            $this->fail('Partial privileged account configuration should be rejected.');
        } catch (RuntimeException $exception) {
            $this->assertSame('All four privileged seed account values must be configured together.', $exception->getMessage());
        }

        $this->assertDatabaseCount('users', 0);
    }

    public function test_default_seeder_rejects_identical_admin_and_superadmin_emails(): void
    {
        config([
            'seeding.admin.email' => 'same@example.com',
            'seeding.admin.password' => 'test-admin-password',
            'seeding.superadmin.email' => 'same@example.com',
            'seeding.superadmin.password' => 'test-superadmin-password',
        ]);

        try {
            $this->seed(DatabaseSeeder::class);
            $this->fail('Identical privileged account emails should be rejected.');
        } catch (RuntimeException $exception) {
            $this->assertSame('Admin and superadmin seed account emails must be different.', $exception->getMessage());
        }

        $this->assertDatabaseCount('users', 0);
    }

    public function test_default_seeder_requires_credentials_in_production(): void
    {
        $this->app->detectEnvironment(fn () => 'production');
        config([
            'seeding.admin.email' => null,
            'seeding.admin.password' => null,
            'seeding.superadmin.email' => null,
            'seeding.superadmin.password' => null,
        ]);

        try {
            app(DatabaseSeeder::class)->run();
            $this->fail('Production seeding without credentials should be rejected.');
        } catch (RuntimeException $exception) {
            $this->assertSame('Privileged seed accounts must be configured in production.', $exception->getMessage());
        }

        $this->assertDatabaseCount('users', 0);
    }

    public function test_default_seeder_rejects_a_configured_email_with_another_role(): void
    {
        User::factory()->create([
            'email' => 'seed-admin@example.com',
            'role' => 'client',
        ]);
        config([
            'seeding.admin.email' => 'seed-admin@example.com',
            'seeding.admin.password' => 'test-admin-password',
            'seeding.superadmin.email' => 'seed-superadmin@example.com',
            'seeding.superadmin.password' => 'test-superadmin-password',
        ]);

        try {
            $this->seed(DatabaseSeeder::class);
            $this->fail('A conflicting account role should be rejected.');
        } catch (RuntimeException $exception) {
            $this->assertSame('The configured email for the admin seed account belongs to another role.', $exception->getMessage());
        }

        $this->assertDatabaseCount('users', 1);
        $this->assertDatabaseHas('users', [
            'email' => 'seed-admin@example.com',
            'role' => 'client',
        ]);
    }
}
