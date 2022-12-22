<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TempLeads extends Model
{
    use HasFactory;
    protected $fillable = [
        'company_name',
        'company_phone_no',
        'company_country_id',
        'company_city_id',
        'industry_id',
        'company_email',
        'department',
        'designation',
        'contact_person_name',
        'contact_person_email',
        'contact_person_phone',
        'dob',
        'address',
        'post_box_no',
        'cp_country_id',
        'cp_city_id',
        'website_name',
        'calling_status',
        'recalling_date',
        'call_type',
        'last_call_comment',
        'tele_caller_id',
        'imported_by',
        'created_by',
        'modified_by',
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function industry()
    {
        return $this->belongsTo(Industry::class);
    }
}
