<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServicePrice extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_id',
        'service_field_id',
        'user_type',
        'price',
    ];

    // A ServicePrice belongs to a Service
    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    // A ServicePrice may belong to a ServiceField
    public function field()
    {
        return $this->belongsTo(ServiceField::class, 'service_field_id');
    }
}
