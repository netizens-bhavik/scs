<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mom extends Model
{
    use HasFactory;
    protected $fillable = [
        'client_id',
        'meeting_date',
        'company_name',
        'contact_person',
        'minutes_of_meeting',
        'bde_feedback',
        'mom_type',
        'followup',
        'share_user_id',
        'mode_of_meeting',
        'next_followup_date',
        'next_followup_time',
        'client_status',
        'created_by',
        'modified_by',
        'is_deleted'
    ];
}
