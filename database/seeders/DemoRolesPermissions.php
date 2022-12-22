<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoRolesPermissions extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $administrator_permissions = [
            'manage_masters',
            'manage_users',
            'user_view',
            'user_add',
            'user_edit',
            'user_delete',
            'manage_city',
            'city_view',
            'city_add',
            'city_edit',
            'city_delete',
            'manage_country',
            'country_view',
            'country_add',
            'country_edit',
            'country_delete',
            'manage_client',
            'client_view',
            'client_add',
            'client_edit',
            'client_delete',
            'manage_industry',
            'industry_view',
            'industry_add',
            'industry_edit',
            'industry_delete',
            'manage_soft_calling',
            'soft_call_upload',
            'soft_call_add',
            'soft_call_incoming',
            'soft_call_outgoing',
            'soft_call_assign',
        ];

        // $director_permissions =[
        //     'manage_city',
        //     'city_view',
        //     'city_add',
        //     'city_edit',
        //     'city_delete',
        //     'manage_country',
        //     'country_view',
        //     'country_add',
        //     'country_edit',
        //     'country_delete',
        //     'manage_client',
        //     'client_view',
        //     'client_add',
        //     'client_edit',
        //     'client_delete',
        //     'manage_industry',
        //     'industry_view',
        //     'industry_add',
        //     'industry_edit',
        //     'industry_delete',
        //     'manage_soft_calling',
        //     'soft_call_upload',
        //     'soft_call_add',
        //     'soft_call_incoming',
        //     'soft_call_outgoing',
        //     'soft_call_assign',
        // ];

        // $general_manager_permissions=[
        //     'manage_client',
        //     'client_view',
        //     'client_add',
        //     'client_edit',
        //     'client_delete',
        //     'manage_soft_calling',
        //     'soft_call_upload',
        //     'soft_call_add',
        //     'soft_call_incoming',
        //     'soft_call_outgoing',
        //     'soft_call_assign',
        // ];

        // $bde_permissions=[
        //     'manage_client',
        //     'client_view',
        //     'client_add',
        //     'client_edit',
        //     'client_delete',
        //     'manage_soft_calling',
        //     'soft_call_upload',
        //     'soft_call_add',
        //     'soft_call_incoming',
        //     'soft_call_outgoing',
        //     'soft_call_assign',
        // ];

        // $bdm_permissions=[
        //     'manage_client',
        //     'client_view',
        //     'client_add',
        //     'client_edit',
        //     'client_delete',
        //     'manage_soft_calling',
        //     'soft_call_upload',
        //     'soft_call_add',
        //     'soft_call_incoming',
        //     'soft_call_outgoing',
        //     'soft_call_assign',
        // ];

        // $softcaller_permissions=[
        //     'manage_soft_calling',
        //     'soft_call_upload',
        //     'soft_call_add',
        //     'soft_call_incoming',
        //     'soft_call_outgoing',
        //     'soft_call_assign',
        // ];

        $administrator = \Spatie\Permission\Models\Role::findByName('administrator');
        $administrator->givePermissionTo($administrator_permissions);

        // $director = \Spatie\Permission\Models\Role::findByName('director');
        // $director->givePermissionTo($director_permissions);

        // $general_manager = \Spatie\Permission\Models\Role::findByName('general_manager');
        // $general_manager->givePermissionTo($general_manager_permissions);

        // $bde = \Spatie\Permission\Models\Role::findByName('bde');
        // $bde->givePermissionTo($bde_permissions);

        // $bdm = \Spatie\Permission\Models\Role::findByName('bdm');
        // $bdm->givePermissionTo($bdm_permissions);

        // $softcaller = \Spatie\Permission\Models\Role::findByName('softcaller');
        // $softcaller->givePermissionTo($softcaller_permissions);
    }
}
