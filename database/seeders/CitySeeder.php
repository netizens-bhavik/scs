<?php

namespace Database\Seeders;

use App\Models\City as ModelsCity;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ModelsCity::create([
            'city_name' => 'Surat',
            'country_id' => '101',
            'created_by'=>1,
            'modified_by'=>1,
        ]);
    }
}
