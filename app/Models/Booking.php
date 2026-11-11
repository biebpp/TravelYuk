<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Booking extends Model
{
    protected $table = 'bookings';
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id',
        'transaction_id',
        'bundle_id',
        'name',
        'date',
        'status',
        'booking_code',
        'idempotency_key',
        'phone',
        'email',
        'departure_date',
        'participants',
        'total_price',
        'payment_status',
        'inventory_reserved',
        'bundle_quota_reserved',
    ];

    protected function casts(): array
    {
        return [
            'departure_date' => 'date',
            'total_price' => 'decimal:2',
            'inventory_reserved' => 'boolean',
            'bundle_quota_reserved' => 'boolean',
        ];
    }

    public function bundle(): BelongsTo
    {
        return $this->belongsTo(TourBundle::class, 'bundle_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(BookingItem::class);
    }
}
