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
        return $this->hasOne(User::class,  'id', 'user_id');
    }
}
