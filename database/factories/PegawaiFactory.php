<?php

namespace Database\Factories;

use App\Models\Pegawai;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Database\Eloquent\Factories\Factory;

class PegawaiFactory extends Factory
{
    protected $model = Pegawai::class;

    public function definition()
    {
        // Digunakan untuk membuat instance dari Faker dengan pengaturan lokal bahasa Indonesia (ID)
        $faker = Faker::create('id_ID');

        // Ambil data dari tabel users atau buat user baru jika belum ada
        $user = User::factory()->create();

        return [
            'user_id' => $user->id,
            'nik' => $faker->numerify('###############'), // 16 angka
            'nip' => $faker->numerify('########'), // 8 angka
            'nama' => $user->name,
            'alamat' => $faker->address,
            'phone' => $faker->phoneNumber,
        ];
    }
}
