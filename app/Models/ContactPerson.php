<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactPerson extends Model
{
    use HasFactory;
    protected $table = 'contact_person';

    protected $fillable = [
        'client_id',
        'name',
        'department',
        'designation',
        'email',
        'mobile_no',
        'dob',
        'is_deleted',
        'created_by',
        'modified_by'
    ];
}
