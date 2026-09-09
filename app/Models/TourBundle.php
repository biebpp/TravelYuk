<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class TourBundle extends Model
{
    use HasFactory;

    protected $fillable = [
        'admin_id',
        'transportation_id',
        'name',
        'description',
        'price',
        'slot',
        'rating',
    ];

    public function destinations(): BelongsToMany
    {
        return $this->belongsToMany(Destination::class, 'bundle_pivots', 'bundle_id', 'destination_id')
            ->withTimestamps();
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
