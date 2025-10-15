<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('penugasan_peserta', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penugasan_id')->constrained('penugasans')->onDelete('cascade');
            $table->foreignId('peserta_id')->constrained('pesertas')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['penugasan_id', 'peserta_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('penugasan_peserta');
    }
};
