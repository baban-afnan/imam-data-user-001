<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'is_active',
    ];

    // A Service has many ServiceFields
    public function fields()
    {
        return $this->hasMany(ServiceField::class);
    }

    // A Service has many ServicePrices
    public function prices()
    {
        return $this->hasMany(ServicePrice::class);
    }
}
