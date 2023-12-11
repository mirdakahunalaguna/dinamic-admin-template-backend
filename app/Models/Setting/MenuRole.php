<?php

namespace App\Models\Setting;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuRole extends Model
{
    use HasFactory;

    protected $table = 'menu_role'; // Sesuaikan dengan nama tabel pivot Anda
    protected $guarded = ['id'];

    public function menus()
    {
        return $this->belongsToMany(Menu::class, 'menu_role', 'role_id', 'menu_id');
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'menu_role', 'menu_id', 'role_id');
    }
}
