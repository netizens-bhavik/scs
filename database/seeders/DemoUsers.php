<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DemoUsers extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
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
//            'manage_mom_mode',
//            'mom_mode_view',
//            'mom_mode_add',
//            'mom_mode_edit',
//            'mom_mode_delete',
            'manage_soft_calling',
            'soft_call_upload',
            'soft_call_add',
            'soft_call_view',
            'soft_call_edit',
            'soft_call_delete',
            'soft_call_incoming',
            'soft_call_outgoing',
            'soft_call_assign',
            'soft_call_view_assigned_leads',
            'manage_mom',
            'mom_view',
            'mom_add',
            'mom_edit',
            'mom_delete',
            'transfer_lead',
            'mom_job_status',
            'manage_report',
            'mom_report',
            'call_status_report',
            'call_status_uw_report',
            'client_status_report',
            'manage_notes'
        ];

        User::create([
            'name' => 'Administrator User',
            'email' => 'admin@admin.com',
            'email_verified_at' => now(),
            'country_id' => '1',
            'city_id' => null,
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        ])->assignRole('administrator')->givePermissionTo($permissions);

        // User::create([
        //     'name' => 'Director User',
        //     'email' => 'dr@dr.com',
        //     'email_verified_at' => now(),
        //     'country_id' => '1',
        //     'city_id' => '1',
        //     'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        // ])->assignRole('director');

        // User::create([
        //     'name' => 'General Manager User',
        //     'email' => 'gm@gm.com',
        //     'email_verified_at' => now(),
        //     'country_id' => '1',
        //     'city_id' => '1',
        //     'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        // ])->assignRole('general manager');

        // User::create([
        //     'name' => 'BDM User',
        //     'email' => 'bdm@bdm.com',
        //     'email_verified_at' => now(),
        //     'country_id' => '1',
        //     'city_id' => '1',
        //     'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        // ])->assignRole('bdm');

        // User::create([
        //     'name' => 'BDE User',
        //     'email' => 'bde@bde.com',
        //     'email_verified_at' => now(),
        //     'country_id' => '1',
        //     'city_id' => '1',
        //     'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        // ])->assignRole('bde');

        // User::create([
        //     'name' => 'Soft Caller User',
        //     'email' => 'sc@sc.com',
        //     'email_verified_at' => now(),
        //     'country_id' => '1',
        //     'city_id' => '1',
        //     'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        // ])->assignRole('softcaller');
    }
}
