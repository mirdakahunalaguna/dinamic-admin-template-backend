<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run()
    {
        $roles = [
            'super admin' => 'mirda_yanuar',
            'admin' => 'admin_user',
            'Chief Executive Officer' => 'ceo_user',
            'Direktur Utama' => 'dirut_user',
            'Manajer Keuangan' => 'manajer_keuangan_user',
            'Staff Keuangan' => 'staff_keuangan_user',
            'Manajer Sumber Daya Manusia' => 'manajer_sdm_user',
            'Staff HRD' => 'staff_hrd_user',
            'Manajer Pemasaran' => 'manajer_pemasaran_user',
            'Staff Pemasaran' => 'staff_pemasaran_user',
            'Manajer Operasional' => 'manajer_operasional_user',
            'Teknisi' => 'teknisi_user',
            'Staff Administrasi' => 'staff_administrasi_user',
            'Staff IT' => 'staff_it_user',
        ];

        foreach ($roles as $roleName => $userName) {
            $role = Role::where('name', $roleName)->first();

            if ($role) {
                $user = User::where('email', $userName . '@gmail.com')->first();

                if (!$user) {
                    $user = new User([
                        'user_name' => $userName,
                        'email' => $userName . '@gmail.com',
                        'password' => bcrypt('secret1234'), // Ganti dengan kata sandi yang sesuai
                    ]);

                    $user->save();
                }

                $user->assignRole($role);

                if ($roleName === 'super admin') {
                    // No need to create a new $user object, use the existing one
                    $user->user_name = 'mirda_yanuar';
                    $user->email = 'mirda@gmail.com';
                    $user->save();
                }
            }
        }
    }
}


