<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Menentukan data yang ingin dimasukkan ke dalam tabel menu_role
        $data = [
            [
                'menu_id' => 1,
                'role_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'menu_id' => 2,
                'role_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'menu_id' => 3,
                'role_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Tambahkan data lain sesuai kebutuhan
        ];

        // Memasukkan data ke dalam tabel menggunakan DB facade
        DB::table('menu_role')->insert($data);
    }
}
