<?php

namespace Database\Seeders;

use Spatie\Permission\Models\Role;
use Illuminate\Database\Seeder;

class Roles extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create(['name' => 'administrator']);
        Role::create(['name' => 'director']);
        Role::create(['name' => 'general manager']);
        Role::create(['name' => 'bde']);
        Role::create(['name' => 'bdm']);
        Role::create(['name' => 'softcaller']);
    }
}
