<?php

namespace Database\Factories;

use App\Models\UserCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserCategoryFactory extends Factory
{
    protected $model = UserCategory::class;

    public function definition()
    {
        return [
            'category_name' => $this->getCategoryName(),
        ];
    }

    private function getCategoryName()
    {
        $categories = [
            'super admin',
            'admin',
            'Chief Executive Officer',
            'Direktur Utama',
            'Manajer Keuangan',
            'Staff Keuangan',
            'Manajer Sumber Daya Manusia',
            'Staff HRD',
            'Manajer Pemasaran',
            'Staff Pemasaran',
            'Manajer Operasional',
            'Teknisi',
            'Staff Administrasi',
            'Staff IT',
        ];


        static $counter = 0;
        $category = $categories[$counter];
        $counter++;

        return $category;
        }
}
