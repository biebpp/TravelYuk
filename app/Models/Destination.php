<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Destination extends Model
{
    use HasFactory;

    protected $table = 'destinations';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name', 'description', 'address'
    ];

    public function tourBundles(): BelongsToMany
    {
        return $this->belongsToMany(TourBundle::class, 'bundle_pivots', 'destination_id', 'bundle_id')
                    ->withTimestamps();
    }
}
