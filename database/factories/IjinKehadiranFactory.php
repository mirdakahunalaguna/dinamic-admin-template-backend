<?php
namespace Database\Factories;

use App\Models\IjinKehadiran;
use App\Models\Pegawai;
use Illuminate\Database\Eloquent\Factories\Factory;

class IjinKehadiranFactory extends Factory
{
    protected $model = IjinKehadiran::class;

    public function definition()
    {
        return [
            'nip' => Pegawai::factory()->create()->nip,
            'tanggal' => $this->faker->date(),
            'jenis_ijin' => $this->faker->randomElement(['ijin masuk', 'ijin pulang', 'ijin keluar']),
            'jam_masuk' => $this->faker->time(),
            'jam_keluar' => $this->faker->time(),
            'status' => $this->faker->boolean(),
        ];
    }
}
