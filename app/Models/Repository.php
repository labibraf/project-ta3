<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Repository extends Model
{
    protected $table = 'repositories';

    protected $fillable = [
        'judul',
        'deskripsi',
        'deskripsi_lengkap',
        'laporan_akhir_id',
        'peserta_id',
        'tahun_magang',
        'bagian',
        'kategori',
        'views',
        'is_published',
        'published_at',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
        'views' => 'integer',
    ];

    /**
     * Relasi ke model LaporanAkhir
     * Setiap repository terhubung dengan 1 laporan akhir
     */
    public function laporanAkhir(): BelongsTo
    {
        return $this->belongsTo(LaporanAkhir::class, 'laporan_akhir_id');
    }

    /**
     * Relasi ke model Peserta
     * Setiap repository dibuat oleh 1 peserta
     */
    public function peserta(): BelongsTo
    {
        return $this->belongsTo(Peserta::class, 'peserta_id');
    }

    /**
     * Scope untuk hanya mengambil repository yang sudah dipublish
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    /**
     * Scope untuk filter berdasarkan tahun
     */
    public function scopeByYear($query, $year)
    {
        return $query->where('tahun_magang', $year);
    }

    /**
     * Scope untuk filter berdasarkan kategori
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('kategori', $category);
    }

    /**
     * Scope untuk filter berdasarkan bagian
     */
    public function scopeByBagian($query, $bagian)
    {
        return $query->where('bagian', $bagian);
    }

    /**
     * Increment views counter
     */
    public function incrementViews()
    {
        $this->increment('views');
    }

    /**
     * Publish repository
     */
    public function publish()
    {
        $this->update([
            'is_published' => true,
            'published_at' => now(),
        ]);
    }

    /**
     * Unpublish repository
     */
    public function unpublish()
    {
        $this->update([
            'is_published' => false,
            'published_at' => null,
        ]);
    }
}
