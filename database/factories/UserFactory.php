<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\UserCategory;
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
        $faker = Faker::create('id_ID');
        $userCategoryIds = UserCategory::pluck('id')->toArray();
        static $counter = 0;
        $category = $userCategoryIds[$counter];
        $counter++;
        return [
            'user_name' => $faker->unique()->userName,
            'email' => $faker->unique()->safeEmail,
            'email_verified_at' => now(),
            'password' => bcrypt('secret1234'),
            'remember_token' => Str::random(10),
            'user_category_id' => $category,
        ];
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
