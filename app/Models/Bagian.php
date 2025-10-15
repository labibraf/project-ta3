<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bagian extends Model
{
    protected $fillable = [
        'nama_bagian'
    ];

    public function peserta()
    {
        return $this->hasMany(Peserta::class);
    }

    public function mentor()
    {
        return $this->hasMany(Mentor::class);
    }
}
