<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     *
     */
    public function run()
    {
        // $this->call(UserSeeder::class);
        // $this->call(AbsensiSeeder::class);
        // $this->call(IjinKehadiranSeeder::class);
        $this->call(RolesTableSeeder::class);
        $this->call(PermissionsTableSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(PegawaisSeeder::class);
        $this->call(MenuSeeder::class);
        $this->call(SubmenuSeeder::class);
        $this->call(MenuRoleSeeder::class);

    }
}
