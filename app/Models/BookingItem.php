<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingItem extends Model
{
    protected $fillable = [
        'booking_id',
        'ticket_id',
        'ticket_name',
        'unit_price',
        'quantity',
        'quota_reserved',
    ];

    protected function casts(): array
    {
        return [
            'unit_price' => 'decimal:2',
            'quota_reserved' => 'boolean',
        ];
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }
}
