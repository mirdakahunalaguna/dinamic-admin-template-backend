<?php

namespace App\Models\Setting;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuRole extends Model
{
    use HasFactory;
    protected $table = 'menu_role'; // Sesuaikan dengan nama tabel pivot Anda
     // Kolom yang harus dilindungi dari assignment massal
    protected $guarded = ['id'];
    // Definisikan hubungan many-to-many dengan model Menu
    public function menus()
    {
        return $this->belongsToMany(Menu::class, 'menu_role', 'menu_id', 'role_id');
    }

    // Definisikan hubungan many-to-many dengan model Role
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'menu_role', 'role_id', 'menu_id');
    }
}
