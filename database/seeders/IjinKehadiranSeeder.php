<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\IjinKehadiran;

class IjinKehadiranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Membuat 10 data ijin kehadiran secara acak
        IjinKehadiran::factory()->count(14)->create();
    }
}
