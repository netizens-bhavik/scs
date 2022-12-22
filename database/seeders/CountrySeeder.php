<?php

namespace Database\Seeders;

use App\Models\Country as ModelsCountry;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {
        ModelsCountry::truncate();

        $countries = [
            ['country_name' => "India"],
        ];

        foreach($countries as $country) {
            ModelsCountry::create(
                $country
            );
        }
    }
}