<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Absensi;
use App\Models\User;

class AbsensiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
   public function run()
    {
       // Menggunakan factories untuk menghasilkan data dummy pada tabel pegawais
        Absensi::factory()->count(1000)->create();
    }
}
