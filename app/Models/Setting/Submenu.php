<?php

namespace App\Models\setting;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Setting\Menu;

class Submenu extends Model
{
    use HasFactory;

    // Kolom yang harus dilindungi dari assignment massal
    protected $guarded = ['id'];

    // Atur nama tabel yang sesuai jika nama tabel berbeda
    protected $table = 'submenus';
     // Definisikan relasi dengan tabel Menu
    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }
}
