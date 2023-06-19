<?php

namespace Database\Factories;

use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @var string
     */
     protected $model = User::class;

    public function definition()
    {
        //digunakan untuk membuat instance dari Faker dengan pengaturan lokal bahasa Indonesia (ID
        $faker = Faker::create('id_ID');
        $validRoles = ['admin', 'pengguna', 'pengunjung'];
        return [
            'name' => $faker->name(),
            'email' => preg_replace('/@example\..*/', '@google.com', $faker->unique()->safeEmail),
            'email_verified_at' => now(),
            'password' => bcrypt('secret1234'),
            'remember_token' => Str::random(10),
            'role' => $this->getNextRole($validRoles),
        ];
    }
    // Mendapatkan peran berikutnya berdasarkan urutan array validRoles
    protected function getNextRole($validRoles)
    {
        static $index = 0;
        $role = $validRoles[$index];
        $index = ($index + 1) % count($validRoles);
        return $role;
    }
    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}
