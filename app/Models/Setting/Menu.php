<?php

namespace App\Models\Setting;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// Import model Role
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles; 
use App\Models\Setting\Submenu;

class Menu extends Model
{
    use HasFactory, HasRoles;

    // Kolom yang harus dilindungi dari assignment massal
    protected $guarded = ['id'];

    // Atur nama tabel yang sesuai jika nama tabel berbeda
    protected $table = 'menus';

       // Definisikan relasi banyak-ke-banyak ke model Role
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
    public function syncRoles($roles)
    {
        return $this->roles()->sync($roles, false);
    }
    // Atur relasi jika diperlukan
    public function submenu()
    {
        return $this->hasMany(Submenu::class);
    }
}
