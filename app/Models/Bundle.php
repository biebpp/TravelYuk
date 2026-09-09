<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bundle extends Model
{
    protected $table = 'tour_bundles';
    protected $primaryKey = 'id';
    protected $fillable = [
        'admin_id', 'transportation_id', 'description', 'name', 'slot', 'rating'
    ];
}
