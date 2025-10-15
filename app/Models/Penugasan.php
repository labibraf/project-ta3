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

    // Method pesertas() yang dibutuhkan untuk fix error - return collection
    public function pesertas()
    {
        // Untuk penugasan divisi, return semua peserta di bagian yang sama
        if ($this->kategori === 'Divisi' && $this->bagian_id) {
            return Peserta::where('bagian_id', $this->bagian_id)->get();
        }

        // Untuk penugasan individu, return peserta yang ditugaskan dalam collection
        if ($this->kategori === 'Individu' && $this->peserta_id) {
            return Peserta::where('id', $this->peserta_id)->get();
        }

        // Return empty collection jika tidak ada kondisi yang terpenuhi
        return collect();
    }

    // Method untuk mendapatkan query builder (jika dibutuhkan)
    public function pesertasQuery()
    {
        // Untuk penugasan divisi, return query builder untuk peserta di bagian yang sama
        if ($this->kategori === 'Divisi' && $this->bagian_id) {
            return Peserta::where('bagian_id', $this->bagian_id);
        }

        // Untuk penugasan individu, return query builder untuk peserta yang ditugaskan
        if ($this->kategori === 'Individu' && $this->peserta_id) {
            return Peserta::where('id', $this->peserta_id);
        }

        // Return empty query builder jika tidak ada kondisi yang terpenuhi
        return Peserta::whereRaw('1 = 0'); // Always returns empty result
    }

    // Method untuk mendapatkan collection peserta (alias untuk backward compatibility)
    public function getPesertasCollection()
    {
        return $this->pesertas();
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

            // Update untuk penugasan divisi (multiple peserta)
            if ($penugasan->kategori === 'Divisi' && $penugasan->bagian_id) {
                $pesertasInBagian = \App\Models\Peserta::where('bagian_id', $penugasan->bagian_id)->get();
                foreach ($pesertasInBagian as $peserta) {
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

            // Update untuk penugasan divisi (multiple peserta)
            if ($penugasan->kategori === 'Divisi' && $penugasan->bagian_id) {
                $pesertasInBagian = \App\Models\Peserta::where('bagian_id', $penugasan->bagian_id)->get();
                foreach ($pesertasInBagian as $peserta) {
                    if (method_exists($peserta, 'updateWaktuTugasTercapai')) {
                        $peserta->updateWaktuTugasTercapai();
                    }
                }
            }
        });
    }
}
