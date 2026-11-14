<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\TourBundle;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class BookingFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_client_can_open_a_package_booking_page_with_unlimited_slots(): void
    {
        $client = User::factory()->create(['role' => 'client']);
        $bundle = $this->createBundle($client, null);

        $response = $this->actingAs($client)->get(route('payment', $bundle));

        $response->assertOk();
        $response->assertSee('Tidak terbatas');
    }

    public function test_client_booking_uses_saved_price_and_reserves_inventory_once(): void
    {
        $client = User::factory()->create(['role' => 'client']);
        $bundle = $this->createBundle($client, 1);
        $bookingKey = (string) Str::uuid();
        $bookingData = [
            'bundle_id' => $bundle->id,
            'idempotency_key' => $bookingKey,
            'name' => 'Package booking',
            'phone' => '+628123456789',
            'email' => $client->email,
            'departure_date' => now()->addWeek()->toDateString(),
            'participants' => 1,
        ];

        $this->actingAs($client)
            ->post(route('client.booking.store'), $bookingData)
            ->assertRedirect(route('client.booking'));

        $booking = Booking::firstOrFail();
        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'status' => 'pending',
            'bundle_id' => $bundle->id,
            'payment_status' => 'unpaid',
            'participants' => 1,
            'total_price' => 250000,
            'inventory_reserved' => true,
        ]);
        $this->assertDatabaseHas('tour_bundles', [
            'id' => $bundle->id,
            'slot' => 0,
        ]);
        $this->get(route('client.booking'))
            ->assertOk()
            ->assertSee($booking->booking_code)
            ->assertSee('Package booking');
        $this->get(route('client.dashboard'))
            ->assertOk()
            ->assertSee($booking->booking_code)
            ->assertSee('Total booking');

        $this->post(route('client.booking.store'), $bookingData)
            ->assertRedirect(route('client.booking'));
        $this->assertDatabaseCount('bookings', 1);
        $this->assertDatabaseHas('tour_bundles', ['id' => $bundle->id, 'slot' => 0]);
    }

    public function test_guest_returns_to_the_selected_package_after_login(): void
    {
        $client = User::factory()->create(['role' => 'client']);
        $bundle = $this->createBundle(User::factory()->create(['role' => 'admin']), 1);

        $this->get(route('packages.booking', $bundle))
            ->assertRedirect(route('login'));

        $this->post(route('login'), [
            'email' => $client->email,
            'password' => 'password',
        ])->assertRedirect(route('packages.booking', $bundle));

        $this->get(route('packages.booking', $bundle))
            ->assertRedirect(route('payment', $bundle));

        $this->get(route('payment', $bundle))
            ->assertOk()
            ->assertSee('name="bundle_id" value="'.$bundle->id.'"', false)
            ->assertSee('Booking Sekarang');
    }

    public function test_new_client_registration_returns_to_the_selected_package(): void
    {
        $bundle = $this->createBundle(User::factory()->create(['role' => 'admin']), 1);

        $this->get(route('packages.booking', $bundle))
            ->assertRedirect(route('login'));

        $this->get(route('register'))
            ->assertOk();

        $this->post(route('register'), [
            'name' => 'New Travel Client',
            'email' => 'new-client@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ])->assertRedirect(route('packages.booking', $bundle));

        $this->assertDatabaseHas('users', [
            'email' => 'new-client@example.com',
            'role' => 'client',
        ]);
        $this->assertAuthenticated();

        $this->get(route('packages.booking', $bundle))
            ->assertRedirect(route('payment', $bundle));
    }

    public function test_client_cannot_cancel_another_clients_booking(): void
    {
        $owner = User::factory()->create(['role' => 'client']);
        $otherClient = User::factory()->create(['role' => 'client']);
        $bundle = $this->createBundle($owner, 1);
        $booking = Booking::create([
            'user_id' => $owner->id,
            'bundle_id' => $bundle->id,
            'name' => 'Private booking',
            'status' => 'payment',
        ]);

        $this->actingAs($otherClient)
            ->delete(route('client.booking.destroy', $booking))
            ->assertNotFound();
    }

    public function test_admin_can_view_and_update_a_booking_status(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $bundle = $this->createBundle($admin, 1);
        $booking = Booking::create([
            'user_id' => $admin->id,
            'bundle_id' => $bundle->id,
            'name' => 'Admin booking',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($admin)
            ->get(route('admin.booking'))
            ->assertOk()
            ->assertSee(route('admin.booking.status', $booking), false);

        $html = $response->getContent();
        $this->assertSame(1, substr_count($html, '<body'));
        $this->assertGreaterThan(strrpos($html, '</header>'), strpos($html, 'Admin booking'));

        $this->patch(route('admin.booking.status', $booking), [
            'status' => 'accepted',
            'payment_status' => 'paid',
        ])->assertRedirect(route('admin.booking'));

        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'status' => 'accepted',
            'payment_status' => 'paid',
        ]);
    }

    public function test_admin_cannot_mark_a_declined_booking_as_paid(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $bundle = $this->createBundle($admin, 0);
        $booking = Booking::create([
            'user_id' => $admin->id,
            'bundle_id' => $bundle->id,
            'name' => 'Reserved booking',
            'status' => 'pending',
            'payment_status' => 'unpaid',
            'participants' => 1,
            'inventory_reserved' => true,
            'bundle_quota_reserved' => true,
        ]);

        $this->actingAs($admin)
            ->from(route('admin.booking'))
            ->patch(route('admin.booking.status', $booking), [
                'status' => 'declined',
                'payment_status' => 'paid',
            ])
            ->assertRedirect(route('admin.booking'))
            ->assertSessionHasErrors('payment_status');

        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'status' => 'pending',
            'payment_status' => 'unpaid',
            'inventory_reserved' => true,
        ]);
        $this->assertDatabaseHas('tour_bundles', [
            'id' => $bundle->id,
            'slot' => 0,
        ]);
    }

    private function createBundle(User $admin, ?int $slot): TourBundle
    {
        return TourBundle::create([
            'admin_id' => $admin->id,
            'name' => 'Test Package',
            'description' => 'Test package description',
            'price' => 250000,
            'slot' => $slot,
            'is_active' => true,
        ]);
    }
}
