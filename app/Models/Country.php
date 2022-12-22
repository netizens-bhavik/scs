<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    protected $fillable = [
        'country_name',
        'created_by',
        'modified_by'
    ];

    public function cities()
    {
        return $this->hasMany(City::class);
    }
}
