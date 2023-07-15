<?php

namespace Database\Factories;

use App\Models\Absensi;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Database\Eloquent\Factories\Factory;

class AbsensiFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Absensi::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $faker = Faker::create('id_ID');
        $userIds = User::pluck('id')->toArray();
        static $counter = 0;
        $user_id = $userIds[$counter % count($userIds)];
        $counter++;

          // Tentukan rentang tanggal dengan tahun dan bulan tertentu
        $startDate = '2023-01-01'; // Tanggal awal
        $endDate = '2023-06-30'; // Tanggal akhir
        return [
        'user_id' => $user_id,
        'tanggal' => $faker->dateTimeBetween($startDate, $endDate)->format('Y-m-d'),
        'jam_masuk' => $faker->time('08:00:00'),
        'jam_keluar' => $faker->time('17:00:00'),
        'updated_at' => $faker->dateTimeBetween('-1 year', 'now'),
        'created_at' => $faker->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
