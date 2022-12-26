<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class ModePermissions extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create(['name' => 'manage_mom_mode']);
        Permission::create(['name' => 'mom_mode_view']);
        Permission::create(['name' => 'mom_mode_add']);
        Permission::create(['name' => 'mom_mode_edit']);
        Permission::create(['name' => 'mom_mode_delete']);
    }
}
