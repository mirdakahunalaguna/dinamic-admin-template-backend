<?php

namespace App\Models;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserCategory extends Model
{
    use HasFactory;

    protected $guarded = [
        'id'
    ];
    public function users()
    {
        return $this->hasMany(User::class, 'user_category_id', 'id');
    }
    public function roles()
{
    return $this->hasMany(Role::class, 'user_category_id', 'id');
}


}
