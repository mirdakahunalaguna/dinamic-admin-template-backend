<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    use HasFactory;

    protected $guarded = ['id']; // Atur kolom yang dapat diisi dalam model

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
        public function ijinKehadirans()
    {
        return $this->hasMany(IjinKehadiran::class, 'nip');
    }
}
