<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //\App\Models\User::factory(5)->create();
        $this->call(Roles::class);
        $this->call(CountrySeeder::class);
      //  $this->call(CitySeeder::class);
        //$this->call(ClientSeeder::class);
        $this->call(Permissions::class);
        $this->call(DemoUsers::class);
        $this->call(DemoRolesPermissions::class);
      //  $this->call(industryseeder::class);
    }
}
