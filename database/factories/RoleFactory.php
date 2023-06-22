<?php
namespace Database\Factories;

use App\Models\Role;
use App\Models\UserCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoleFactory extends Factory
{
    protected $model = Role::class;

    public function definition()
    {
        $userCategoryIds = UserCategory::pluck('id')->toArray();        static $counter = 0;
        $category = $userCategoryIds[$counter];
        $counter++;
        return [
            'user_category_id' => $category,
            'uri' => $this->faker->url,
            'read' => $this->faker->boolean,
            'create' => $this->faker->boolean,
            'update' => $this->faker->boolean,
            'delete' => $this->faker->boolean,
        ];
    }
}
