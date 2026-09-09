<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    protected $table = 'bookings';
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id', 'transition_id', 'bundle_id', 'name', 'date', 'status'
    ];

    public function bundle(): BelongsTo {
        return $this->belongsTo(TourBundle::class, 'bundle_id');
    }

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }
}
