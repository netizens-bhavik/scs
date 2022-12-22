<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MomMode extends Model
{
    use HasFactory;

    protected $fillable = [
        'mode_name',
        'created_by',
        'modified_by',
        'is_deleted'
    ];
}
