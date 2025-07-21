<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Peserta extends Model
{
    use HasFactory;
    protected $guarded = ['peserta_id'];

    public function user()
    {
        return $this->hasOne(User::class, 'peserta_id');
    }
    public function bagian()
    {
        return $this->belongsTo(Bagian::class);
    }
}
