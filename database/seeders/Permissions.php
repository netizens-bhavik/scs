<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class Permissions extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create(['name' => 'manage_masters']);

        Permission::create(['name' => 'manage_users']);
        Permission::create(['name' => 'user_view']);
        Permission::create(['name' => 'user_add']);
        Permission::create(['name' => 'user_edit']);
        Permission::create(['name' => 'user_delete']);

        Permission::create(['name' => 'manage_city']);
        Permission::create(['name' => 'city_view']);
        Permission::create(['name' => 'city_add']);
        Permission::create(['name' => 'city_edit']);
        Permission::create(['name' => 'city_delete']);

        Permission::create(['name' => 'manage_country']);
        Permission::create(['name' => 'country_view']);
        Permission::create(['name' => 'country_add']);
        Permission::create(['name' => 'country_edit']);
        Permission::create(['name' => 'country_delete']);

        Permission::create(['name' => 'manage_client']);
        Permission::create(['name' => 'client_view']);
        Permission::create(['name' => 'client_add']);
        Permission::create(['name' => 'client_edit']);
        Permission::create(['name' => 'client_delete']);

        Permission::create(['name' => 'manage_industry']);
        Permission::create(['name' => 'industry_view']);
        Permission::create(['name' => 'industry_add']);
        Permission::create(['name' => 'industry_edit']);
        Permission::create(['name' => 'industry_delete']);

        Permission::create(['name' => 'manage_mom_mode']);
        Permission::create(['name' => 'mom_mode_view']);
        Permission::create(['name' => 'mom_mode_add']);
        Permission::create(['name' => 'mom_mode_edit']);
        Permission::create(['name' => 'mom_mode_delete']);

        Permission::create(['name' => 'manage_soft_calling']);
        Permission::create(['name' => 'soft_call_upload']);
        Permission::create(['name' => 'soft_call_add']);
        Permission::create(['name' => 'soft_call_view']);
        Permission::create(['name' => 'soft_call_edit']);
        Permission::create(['name' => 'soft_call_delete']);
        Permission::create(['name' => 'soft_call_incoming']);
        Permission::create(['name' => 'soft_call_outgoing']);

        Permission::create(['name' => 'soft_call_assign']);
        Permission::create(['name' => 'soft_call_view_assigned_leads']);
        Permission::create(['name' => 'manage_mom']);
        Permission::create(['name' => 'mom_add']);
        Permission::create(['name' => 'mom_view']);
        Permission::create(['name' => 'mom_edit']);
        Permission::create(['name' => 'mom_delete']);

        Permission::create(['name' => 'transfer_lead']);

        Permission::create(['name' => 'mom_job_status']);

        Permission::create(['name' => 'manage_report']);
        Permission::create(['name' => 'mom_report']);
        Permission::create(['name' => 'call_status_report']);
        Permission::create(['name' => 'call_status_uw_report']);
        Permission::create(['name' => 'client_status_report']);

        Permission::create(['name' => 'manage_notes']);
    }
}
