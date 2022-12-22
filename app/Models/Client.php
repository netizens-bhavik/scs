<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'lead_id',
        'company_name',
        'country_id',
        'industry_id',
        'city_id',
        'address',
        'post_box_no',
        'phone_no',
        'email',
        'website_name',
        'sort_description',
        'active_status',
        'is_deleted',
        'manage_by',
        'created_by',
        'modified_by'
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
