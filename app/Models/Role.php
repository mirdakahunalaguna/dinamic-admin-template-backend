<?php

namespace App\Models;

use App\Models\User;
use App\Models\UserCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends Model
{
    use HasFactory;

    protected $guarded = [
        'id'
    ];
       // Relasi dengan model Pegawai
    public function user()
    {
        return $this->belongsTo(User::class);
    }
          // Relasi dengan model Pegawai
    public function userCategory()
{
    return $this->belongsTo(UserCategory::class, 'user_category_id', 'id');
}

}
