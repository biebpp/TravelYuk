<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Ticket;
use App\Models\TourBundle;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\ValidationException;

class BookingInventory
{
    /**
     * Reserve package and ticket inventory inside the caller's transaction.
     *
     * @return array{bundle_quota_reserved: bool, ticket_quota_reserved: array<int, bool>}
     */
    public function reserve(TourBundle $bundle, Collection $tickets, int $participants): array
    {
        if (! $bundle->is_active) {
            throw ValidationException::withMessages([
                'bundle_id' => 'Paket ini sedang tidak tersedia untuk booking.',
            ]);
        }

        if ($bundle->slot !== null && $bundle->slot < $participants) {
            throw ValidationException::withMessages([
                'participants' => 'Jumlah peserta melebihi sisa kuota paket.',
            ]);
        }

        foreach ($tickets as $ticket) {
            if (! $ticket->is_active) {
                throw ValidationException::withMessages([
                    'bundle_id' => "Tiket {$ticket->name} pada paket ini sedang tidak tersedia.",
                ]);
            }

            if ($ticket->quota !== null && $ticket->quota < $participants) {
                throw ValidationException::withMessages([
                    'participants' => "Sisa kuota tiket {$ticket->name} tidak mencukupi.",
                ]);
            }
        }

        $bundleQuotaReserved = $bundle->slot !== null;
        if ($bundleQuotaReserved) {
            $bundle->decrement('slot', $participants);
        }

        $ticketQuotaReserved = [];
        foreach ($tickets as $ticket) {
            $ticketQuotaReserved[$ticket->id] = $ticket->quota !== null;
            if ($ticketQuotaReserved[$ticket->id]) {
                $ticket->decrement('quota', $participants);
            }
        }

        return [
            'bundle_quota_reserved' => $bundleQuotaReserved,
            'ticket_quota_reserved' => $ticketQuotaReserved,
        ];
    }

    public function release(Booking $booking): void
    {
        if (! $booking->inventory_reserved) {
            return;
        }

        if ($booking->bundle_quota_reserved && $booking->bundle_id !== null) {
            $bundle = TourBundle::query()->lockForUpdate()->find($booking->bundle_id);
            if ($bundle && $bundle->slot !== null) {
                $bundle->increment('slot', $booking->participants);
            }
        }

        $items = $booking->items()
            ->where('quota_reserved', true)
            ->whereNotNull('ticket_id')
            ->orderBy('ticket_id')
            ->get();

        $tickets = Ticket::query()
            ->whereIn('id', $items->pluck('ticket_id'))
            ->orderBy('id')
            ->lockForUpdate()
            ->get()
            ->keyBy('id');

        foreach ($items as $item) {
            $ticket = $tickets->get($item->ticket_id);
            if ($ticket && $ticket->quota !== null) {
                $ticket->increment('quota', $item->quantity);
            }
        }

        $booking->update([
            'inventory_reserved' => false,
            'bundle_quota_reserved' => false,
        ]);
    }
}
