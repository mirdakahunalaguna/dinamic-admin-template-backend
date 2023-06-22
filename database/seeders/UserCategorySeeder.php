<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UserCategory;

class UserCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        UserCategory::factory()->count(14)->create();
    }
}
