<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pegawai;

class PegawaisSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
       // Menggunakan factories untuk menghasilkan data dummy pada tabel pegawais
        Pegawai::factory()->count(12)->create();
    }
}
