<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
    public function category()
    {
        return $this->belongsTo(UserCategory::class);
    }
}
