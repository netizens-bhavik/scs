<?php

namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Client::create([
            'lead_id' => '',
            'company_name' => 'testCompany1',
            'industry_id' => '1',
            'country_id' => '1',
            'city_id' => '1',
            'address' => 'testAddress',
            'post_box_no' => '123456789',
            'phone_no' => '3452167890',
            'email' => 'test@gmail.com',
            'website_name' => 'testtest.com',
            'sort_description' => 'testttt',
            'active_status' => '1',
            'created_by' => '1'
        ]);
    }
}
