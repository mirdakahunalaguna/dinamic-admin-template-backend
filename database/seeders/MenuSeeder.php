<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting\Menu;

class MenuSeeder extends Seeder
{
    public function run()
    {
        // Data awal untuk tabel Menu
        Menu::create([
            'title' => 'Dasboard',
            'to' => 'Dashboard',
            'icon' =>'mdi:view-grid'
        ]);

        Menu::create([
            'title' => 'Setting Sidebar',
            'to' => 'PageNotFound',
            'icon' =>'icon-park-solid:setting-two'
        ]);

        Menu::create([
            'title' => 'Setting Role',
            'to' => 'PageNotFound',
            'icon' =>'carbon:user-role'
        ]);

        // Tambahkan data lain sesuai kebutuhan
    }
}
