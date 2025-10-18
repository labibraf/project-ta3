<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penugasan extends Model
{
    protected $table = 'penugasans';
    protected $fillable = [
        'judul_tugas',
        'deskripsi_tugas',
        'deadline',
        'status_tugas',
        'feedback',
        'catatan',
        'file',
        'beban_waktu',
        'kategori',
        'mentor_id',
        'bagian_id',
        'peserta_id',
        'is_approved',
    ];

    protected $casts = [
        'deadline' => 'date',
    ];

    public function peserta()
    {
        return $this->belongsTo(Peserta::class, 'peserta_id', 'id');
    }

    public function mentor()
    {
        return $this->belongsTo(Mentor::class, 'mentor_id', 'id');
    }

    public function bagian()
    {
        return $this->belongsTo(Bagian::class, 'bagian_id', 'id');
    }

    public function laporanHarian()
    {
        return $this->hasMany(LaporanHarian::class, 'penugasan_id', 'id');
    }

    // Relasi many-to-many dengan Peserta melalui pivot table
    public function pesertasRelation()
    {
        return $this->belongsToMany(Peserta::class, 'penugasan_peserta', 'penugasan_id', 'peserta_id')
                    ->withTimestamps();
    }

    // Method pesertas() untuk mendapatkan collection peserta yang ditugaskan
    public function pesertas()
    {
        // Untuk penugasan divisi, return peserta yang sudah di-assign (dari pivot table)
        if ($this->kategori === 'Divisi') {
            // Gunakan property accessor untuk mendapatkan collection
            return $this->pesertasRelation()->get();
        }

        // Untuk penugasan individu, return peserta yang ditugaskan dalam collection
        if ($this->kategori === 'Individu' && $this->peserta_id) {
            return Peserta::where('id', $this->peserta_id)->get();
        }

        // Return empty collection jika tidak ada kondisi yang terpenuhi
        return collect();
    }

    // Accessor untuk Ditugaskan (untuk backward compatibility)
    public function getDitugaskanAttribute()
    {
        if ($this->kategori === 'Individu' && $this->peserta) {
            return $this->peserta->user->name;
        } elseif ($this->kategori === 'Divisi') {
            if ($this->peserta) {
                return $this->peserta->user->name;
            } else {
                return 'Divisi '. ($this->bagian->nama_bagian ?? 'bagian ini');
            }
        }
        return $this->peserta ? $this->peserta->user->name : 'Tidak ada peserta';
    }



    // Alias untuk backward compatibility
    public function laporanHarians()
    {
        return $this->laporanHarian();
    }


    protected static function booted()
    {
        static::saved(function ($penugasan) {
            // Update untuk penugasan individu
            if ($penugasan->peserta && method_exists($penugasan->peserta, 'updateWaktuTugasTercapai')) {
                $penugasan->peserta->updateWaktuTugasTercapai();
            }

            // Update untuk penugasan divisi (peserta yang di-assign via pivot table)
            if ($penugasan->kategori === 'Divisi') {
                $pesertasAssigned = $penugasan->pesertasRelation;
                foreach ($pesertasAssigned as $peserta) {
                    if (method_exists($peserta, 'updateWaktuTugasTercapai')) {
                        $peserta->updateWaktuTugasTercapai();
                    }
                }
            }
        });

        static::deleted(function ($penugasan) {
            // Update untuk penugasan individu
            if ($penugasan->peserta && method_exists($penugasan->peserta, 'updateWaktuTugasTercapai')) {
                $penugasan->peserta->updateWaktuTugasTercapai();
            }

            // Update untuk penugasan divisi (peserta yang di-assign via pivot table)
            if ($penugasan->kategori === 'Divisi') {
                $pesertasAssigned = $penugasan->pesertasRelation;
                foreach ($pesertasAssigned as $peserta) {
                    if (method_exists($peserta, 'updateWaktuTugasTercapai')) {
                        $peserta->updateWaktuTugasTercapai();
                    }
                }
            }
        });
    }
}
