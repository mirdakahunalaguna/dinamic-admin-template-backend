<?php
namespace App\Models;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    use HasFactory;
    protected $guarded = [
        'id'
    ];
    protected $fillable = [
        'user_id',
        'tanggal',
        'jam_masuk',
        'jam_keluar',
    ];

    // Relasi dengan model Pegawai
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
