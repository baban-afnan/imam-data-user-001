<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Wallet extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'balance',
        'hold_amount',
        'available_balance',
        'wallet_number',
        'currency',
        'status',
        'last_activity',
    ];

    /**
     * Relationship: A wallet belongs to a user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

