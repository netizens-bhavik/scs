<?php

namespace Database\Seeders;

use App\Models\Industry;
use Illuminate\Database\Seeder;

class industryseeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Industry::create([
            'industry_name' => 'Information Technologies',
            'created_by'=>1,
            'modified_by'=>1,
        ]);
    }
}
