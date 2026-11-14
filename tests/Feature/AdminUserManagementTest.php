<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\TourBundle;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminUserManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_superadmin_can_create_update_and_delete_a_user(): void
    {
        $superadmin = User::factory()->create(['role' => 'superadmin']);

        $this->actingAs($superadmin)
            ->get(route('admin.users.dashboard'))
            ->assertOk();

        $this->post(route('admin.users.store'), [
            'name' => 'Travel Admin',
            'email' => 'travel-admin@example.com',
            'password' => 'Strong-pass-123',
            'role' => 'admin',
        ])->assertRedirect();

        $user = User::where('email', 'travel-admin@example.com')->firstOrFail();

        $this->patch(route('admin.users.update', $user), [
            'name' => 'Updated Travel Admin',
            'email' => 'updated-admin@example.com',
            'role' => 'client',
        ])->assertRedirect();

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Travel Admin',
            'email' => 'updated-admin@example.com',
            'role' => 'client',
        ]);

        $this->delete(route('admin.users.destroy', $user))
            ->assertRedirect();

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    public function test_admin_cannot_access_superadmin_user_management(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->get(route('admin.users.dashboard'))
            ->assertRedirect(route('admin.dashboard'));
    }

    public function test_deleting_an_admin_cascades_through_owned_packages_and_bookings(): void
    {
        $superadmin = User::factory()->create(['role' => 'superadmin']);
        $admin = User::factory()->create(['role' => 'admin']);
        $client = User::factory()->create(['role' => 'client']);
        $bundle = TourBundle::create([
            'admin_id' => $admin->id,
            'name' => 'Admin-owned package',
            'description' => 'Package with booking history',
            'price' => 500000,
        ]);
        $booking = Booking::create([
            'user_id' => $client->id,
            'bundle_id' => $bundle->id,
            'name' => 'Client booking',
            'status' => 'pending',
        ]);

        $this->actingAs($superadmin)
            ->delete(route('admin.users.destroy', $admin))
            ->assertRedirect();

        $this->assertDatabaseMissing('users', ['id' => $admin->id]);
        $this->assertDatabaseMissing('tour_bundles', ['id' => $bundle->id]);
        $this->assertDatabaseMissing('bookings', ['id' => $booking->id]);
    }
}
