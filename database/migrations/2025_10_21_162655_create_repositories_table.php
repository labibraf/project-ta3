<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('repositories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('laporan_akhir_id')->constrained('laporan_akhirs')->onDelete('cascade'); // Relasi ke laporan akhir
            $table->foreignId('peserta_id')->constrained('pesertas')->onDelete('cascade'); // Pembuat (peserta)
            $table->string('judul'); // Judul repository
            $table->text('deskripsi'); // Deskripsi singkat repository
            $table->text('deskripsi_lengkap')->nullable(); // Deskripsi detail (bisa panjang)
            $table->string('tahun_magang', 4); // Tahun pelaksanaan magang (misal: 2024, 2025)
            $table->string('bagian')->nullable(); // Bagian/Divisi tempat magang
            $table->string('kategori')->nullable(); // Kategori repository (misal: Teknik, Non-Teknik, dll)
            $table->integer('views')->default(0); // Jumlah views/kunjungan
            $table->boolean('is_published')->default(false); // Status publikasi (draft/published)
            $table->timestamp('published_at')->nullable(); // Tanggal publikasi
            $table->timestamps();

            // Index untuk optimasi query
            $table->index('tahun_magang');
            $table->index('bagian');
            $table->index('kategori');
            $table->index('is_published');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('repositories');
    }
};
