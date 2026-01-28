<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'recipient_type',
        'recipient_data',
        'subject',
        'message',
        'attachment',
        'is_active',
        'status',
        'performed_by',
        'approved_by',
    ];

    /**
     * Get the active announcement.
     *
     * @return Announcement|null
     */
    public static function getActiveAnnouncement()
    {
        return self::where('type', 'announcement')
                   ->where('is_active', true)
                   ->latest()
                   ->first();
    }
}
