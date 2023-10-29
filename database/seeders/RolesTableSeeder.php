<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesTableSeeder extends Seeder
{
    public function run()
    {
        $roles = [
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

        foreach ($roles as $role) {
            Role::create(['name' => $role, 'guard_name' => 'web']);
        }
    }
}
