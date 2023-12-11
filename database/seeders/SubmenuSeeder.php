<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting\Submenu;

class SubmenuSeeder extends Seeder
{
    public function run()
    {
        // Data awal untuk tabel Submenu
        Submenu::create([
            'menu_id' => 2, // Menu ID yang terkait
            'title' => 'Setting Menu',
            'to' => 'SettingMenu',
        ]);

        Submenu::create([
            'menu_id' => 2, // Menu ID yang terkait
            'title' => 'Setting Submenu',
            'to' => 'SettingSubmenu',
        ]);

        Submenu::create([
            'menu_id' => 3, // Menu ID yang terkait
            'title' => 'Role and Permission',
            'to' => 'SettingRole',
        ]);

        // Tambahkan data lain sesuai kebutuhan
    }
}
