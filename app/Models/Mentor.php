<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mentor extends Model
{
    protected $fillable = [
        'user_id',
        'bagian_id',
        'nama_mentor',
        'email',
        'no_telepon',
        'nomor_identitas',
        'jenis_kelamin',
        'keahlian',
        'alamat',
        'foto',
        'nama_lengkap',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bagian()
    {
        return $this->belongsTo(Bagian::class);
    }
    public function peserta()
    {
        return $this->hasMany(Peserta::class);
    }
    public function laporanAkhir()
    {
        return $this->hasMany(LaporanAkhir::class);
    }
}
