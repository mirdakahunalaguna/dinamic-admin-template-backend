<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Pegawai;

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
        // Panggil seeder yang ingin Anda jalankan di sini
        // $this->call(UsersSeeder::class);
        $this->call(PegawaisSeeder::class);

    }
}
