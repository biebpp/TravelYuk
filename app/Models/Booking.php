<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $table = 'bookings';
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id', 'transition_id', 'bundle_id', 'name', 'date', 'status'
    ];
}
