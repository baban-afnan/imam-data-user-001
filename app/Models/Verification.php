<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Verification extends Model
{
    protected $table = 'verifications';

   protected $fillable = [
    'reference',
    'user_id',
    'service_field_id',
    'service_id',
    'field_code',
    'field_name',
    'service_name',
    'service_type',
    'description',
    'amount',
    'firstname',
    'middlename',
    'surname',
    'gender',
    'birthdate',
    'birthstate',
    'birthlga',
    'birthcountry',
    'maritalstatus',
    'email',
    'telephoneno',
    'residence_address',
    'residence_state',
    'residence_lga',
    'residence_town',
    'religion',
    'employmentstatus',
    'educationallevel',
    'profession',
    'height',
    'title',
    'nin',
    'number_nin',
    'vnin',
    'photo_path',
    'signature_path',
    'trackingId',
    'userid',
    'nok_firstname',
    'nok_middlename',
    'nok_surname',
    'nok_address1',
    'nok_address2',
    'nok_lga',
    'nok_state',
    'nok_town',
    'nok_postalcode',
    'self_origin_state',
    'self_origin_lga',
    'self_origin_place',
    'performed_by',
    'approved_by',
    'tax_id',
    'comment',
    'response_data',
    'modification_data',
    'transaction_id',
    'submission_date',
    'status',
    'idno',
];


    protected $casts = [
        'submission_date' => 'datetime',
        'response_data' => 'array',
        'modification_data' => 'array',
    ];

    // Relationships
    public function serviceField()
    {
        return $this->belongsTo(ServiceField::class);
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
