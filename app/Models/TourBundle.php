<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TourBundle extends Model
{
    use HasFactory;

    protected $table = 'tour_bundles';
    protected $fillable = [
        'admin_id',
        'transportation_id',
        'name',
        'description',
        'price',
        'slot',
        'rating',
        'image_path',
        'duration_days',
        'duration_nights',
        'facilities',
        'departure_date',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'facilities' => 'array',
            'departure_date' => 'date',
            'is_active' => 'boolean',
        ];
    }

    public function destinations(): BelongsToMany
    {
        return $this->belongsToMany(Destination::class, 'bundle_pivots', 'bundle_id', 'destination_id')
            ->withTimestamps();
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function tickets(): BelongsToMany
    {
        return $this->belongsToMany(Ticket::class, 'bundle_tickets', 'bundle_id', 'ticket_id')
            ->with('destination')
            ->withTimestamps();
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'bundle_id');
    }
}
