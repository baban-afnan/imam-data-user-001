<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceField extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_id',
        'field_name',
        'field_code',
        'network_code',
        'description',
        'base_price',
        'status',
        'is_active',
    ];

    // A ServiceField belongs to a Service
    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    // A ServiceField can have many ServicePrices
    public function prices()
    {
        return $this->hasMany(ServicePrice::class, 'service_field_id');
    }

    public function getPriceForUserType($userType)
    {
        return $this->prices()
            ->where('user_type', $userType)
            ->value('price') ?? $this->base_price;
    }
}
