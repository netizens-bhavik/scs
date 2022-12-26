<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class ModePermissionsUser extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
        'manage_mom_mode',
            'mom_mode_view',
            'mom_mode_add',
            'mom_mode_edit',
            'mom_mode_delete'];
        $user = User::find(1);
        $user->givePermissionTo($permissions);
    }
}
