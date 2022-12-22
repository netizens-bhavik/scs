<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MomJob extends Model
{
    use HasFactory;
    protected $table = 'mom_jobs';

    protected $fillable = [
        'mom_id',
        'job_category',
        'quantity',
        'job_description',
        'next_meeting_date',
        'next_meeting_time',
        'created_by',
        'modified_by',
        'is_deleted'
    ];
}
