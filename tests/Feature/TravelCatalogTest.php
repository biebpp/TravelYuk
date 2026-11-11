<?php

namespace Tests\Feature;

use App\Models\Destination;
use App\Models\Ticket;
use App\Models\TourBundle;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TravelCatalogTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_update_and_delete_a_ticket(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $destination = $this->createDestination('Bali');
        $otherDestination = $this->createDestination('Lombok');

        $this->actingAs($admin)->get(route('admin.tickets'))->assertOk();

        $this->post(route('admin.tickets.store'), [
            'destination_id' => $destination->id,
            'name' => 'Tiket Pantai',
            'type' => 'admission',
            'description' => 'Akses pantai',
            'price' => 75000,
            'quota' => 20,
            'is_active' => true,
        ])->assertRedirect();

        $ticket = Ticket::firstOrFail();
        $this->assertSame('Tiket Pantai', $ticket->name);

        $this->patch(route('admin.tickets.update', $ticket), [
            'destination_id' => $otherDestination->id,
            'name' => 'Tiket Pantai Baru',
            'type' => 'admission',
            'description' => 'Akses pantai terbaru',
            'price' => 90000,
            'quota' => 10,
            'is_active' => true,
        ])->assertRedirect();

        $this->assertDatabaseHas('tickets', [
            'id' => $ticket->id,
            'destination_id' => $otherDestination->id,
            'name' => 'Tiket Pantai Baru',
            'price' => 90000,
        ]);

        $this->delete(route('admin.tickets.destroy', $ticket))->assertRedirect();
        $this->assertDatabaseMissing('tickets', ['id' => $ticket->id]);
    }

    public function test_admin_can_attach_destination_tickets_to_a_package(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $destination = $this->createDestination('Bali');
        $otherDestination = $this->createDestination('Lombok');
        $ticket = $this->createTicket($destination);
        $unrelatedTicket = $this->createTicket($otherDestination);

        $this->actingAs($admin)->post(route('admin.bundles.store'), [
            'name' => 'Paket Bali',
            'description' => 'Paket pantai Bali',
            'price' => 500000,
            'slot' => 5,
            'duration_days' => 2,
            'duration_nights' => 1,
            'is_active' => true,
            'destination_ids' => [$destination->id],
            'ticket_ids' => [$ticket->id],
        ])->assertRedirect();

        $bundle = TourBundle::firstOrFail();
        $this->assertDatabaseHas('bundle_pivots', [
            'bundle_id' => $bundle->id,
            'destination_id' => $destination->id,
        ]);
        $this->assertDatabaseHas('bundle_tickets', [
            'bundle_id' => $bundle->id,
            'ticket_id' => $ticket->id,
        ]);
        $this->get(route('admin.bundles'))->assertOk()->assertSee('Tiket Bali');

        $this->post(route('admin.bundles.store'), [
            'name' => 'Paket tidak valid',
            'description' => 'Tiket bukan dari destinasi paket',
            'price' => 500000,
            'duration_days' => 2,
            'duration_nights' => 1,
            'is_active' => true,
            'destination_ids' => [$destination->id],
            'ticket_ids' => [$unrelatedTicket->id],
        ])->assertSessionHasErrors('ticket_ids');
        $this->assertDatabaseCount('tour_bundles', 1);
    }

    public function test_package_catalog_searches_filters_and_displays_included_tickets(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $bali = $this->createDestination('Bali');
        $lombok = $this->createDestination('Lombok');
        $ticket = $this->createTicket($bali);
        $baliBundle = $this->createBundle($admin, 'Pantai Bali', 300000, $bali, $ticket);
        $this->createBundle($admin, 'Bukit Lombok', 900000, $lombok);

        $response = $this->get(route('packages', [
            'q' => 'pantai',
            'destination_id' => $bali->id,
            'min_price' => 200000,
            'max_price' => 500000,
            'sort' => 'price_asc',
        ]));

        $response->assertOk()
            ->assertSee('Pantai Bali')
            ->assertSee('Tiket '.$bali->name)
            ->assertDontSee('Bukit Lombok');

        $this->actingAs(User::factory()->create(['role' => 'client']))
            ->get(route('payment', $baliBundle))
            ->assertOk()
            ->assertSee('Tidak terbatas')
            ->assertSee($ticket->name)
            ->assertSee($bali->name);
    }

    public function test_home_page_uses_the_reference_hero_background_and_booking_action(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $bundle = TourBundle::create([
            'admin_id' => $admin->id,
            'name' => 'Paket Pantai',
            'description' => 'Liburan di pantai',
            'price' => 300000,
            'is_active' => true,
        ]);

        $this->get(route('index'))
            ->assertOk()
            ->assertSee('images/cd11556e-4fed-4fc5-b592-8f97cfdf2a15.png', false)
            ->assertSee('background-size: cover;', false)
            ->assertSee('background-position: center;', false)
            ->assertSee(route('packages.booking', $bundle), false)
            ->assertSee('Cari paket');
    }

    private function createDestination(string $name): Destination
    {
        return Destination::create([
            'name' => $name,
            'address' => $name.', Indonesia',
            'description' => 'Destinasi '.$name,
        ]);
    }

    private function createTicket(Destination $destination): Ticket
    {
        return Ticket::create([
            'destination_id' => $destination->id,
            'name' => 'Tiket '.$destination->name,
            'description' => 'Akses wisata',
            'price' => 50000,
        ]);
    }

    private function createBundle(
        User $admin,
        string $name,
        int $price,
        Destination $destination,
        ?Ticket $ticket = null,
    ): TourBundle {
        $bundle = TourBundle::create([
            'admin_id' => $admin->id,
            'name' => $name,
            'description' => 'Deskripsi '.$name,
            'price' => $price,
            'is_active' => true,
        ]);
        $bundle->destinations()->attach($destination);

        if ($ticket) {
            $bundle->tickets()->attach($ticket);
        }

        return $bundle;
    }
}
