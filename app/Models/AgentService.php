<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgentService extends Model
{
    use HasFactory;

    protected $table = 'agent_services';

    protected $fillable = [
        'reference',
        'user_id',
        'service_id',
        'service_field_id',
        'transaction_id',
        'service_type',
        'field_code',
        'ticket_id',
        'batch_id',
        'request_id',
        'our_id',
        'tracking_id',
        'request_email',
        'email_auth',
        'other_bank',
        'bvn',
        'nin',
        'number',
        'first_name',
        'last_name',
        'middle_name',
        'gender',
        'dob',
        'email',
        'lga',
        'amount',
        'state',
        'field_name',
        'service_name',
        'service_field_name',
        'bank',
        'description',
        'affidavit',
        'affidavit_file_url',
        'file_url',
        'passport_url',
        'nin_slip_url',
        'cac_file',
        'memart_file',
        'status_report_file',
        'tin_file',
        'field',
        'performed_by',
        'approved_by',
        'completed_by',
        'amount',
        'submission_date',
        'status',
        'comment',

        // New Columns
        'company_name',
        'registration_number',
        'phone_number',
        'city',
        'house_number',
        'street_name',
        'country',
        'cac_certificate',
        'company_type',
        'modification_data',
    ];

    protected $casts = [
        'submission_date' => 'datetime',
        'modification_data' => 'array',
    ];

    /** Relationships */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function serviceField()
    {
        return $this->belongsTo(ServiceField::class, 'field_code');
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
