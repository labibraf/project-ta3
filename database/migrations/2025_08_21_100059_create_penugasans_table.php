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
        Schema::create('penugasans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('peserta_id')->nullable()->constrained('pesertas')->nullOnDelete();
            $table->foreignId('mentor_id')->nullable()->constrained('mentors')->nullOnDelete();
            $table->foreignId('bagian_id')->nullable()->constrained('bagians')->nullOnDelete();
            $table->string('judul_tugas');
            $table->text('deskripsi_tugas');
            $table->enum('kategori', ['Individu', 'Divisi']);
            $table->tinyInteger('bobot_tugas')->unsigned()->nullable();
            $table->date('deadline');
            $table->enum('status_tugas', ['Belum', 'Dikerjakan', 'Selesai'])->default('Belum');
            $table->text('feedback')->nullable();
            $table->decimal('nilai_kualitas', 4, 2)->nullable()->default(0.00);
            $table->string('file')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penugasans');
    }
};
