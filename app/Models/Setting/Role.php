<?php

namespace App\Models\Setting;

use Spatie\Permission\Models\Role as SpatieRole;
use Spatie\Permission\Traits\HasRoles;

class Role extends SpatieRole
{
    use HasRoles;

    public function menus()
    {
        return $this->belongsToMany(Menu::class);
    }
}
