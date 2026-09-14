<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = 'transactions';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id', 'user_id', 'payment', 'nominal', 'transaction_date', 'status'
    ];
}
