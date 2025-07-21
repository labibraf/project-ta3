<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bagian extends Model
{
    public function peserta()
    {
        return $this->hasMany(Peserta::class);
    }
}
