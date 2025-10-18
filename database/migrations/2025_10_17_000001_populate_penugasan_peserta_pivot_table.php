<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Populate pivot table for existing division tasks based on bagian_id
     */
    public function up()
    {
        // Get all division category tasks
        $penugasans = DB::table('penugasans')
            ->where('kategori', 'Divisi')
            ->whereNotNull('bagian_id')
            ->get();

        foreach ($penugasans as $penugasan) {
            // Get all peserta in the same bagian
            $pesertaIds = DB::table('pesertas')
                ->where('bagian_id', $penugasan->bagian_id)
                ->pluck('id');

            // Insert into pivot table
            foreach ($pesertaIds as $pesertaId) {
                // Check if record already exists
                $exists = DB::table('penugasan_peserta')
                    ->where('penugasan_id', $penugasan->id)
                    ->where('peserta_id', $pesertaId)
                    ->exists();

                if (!$exists) {
                    DB::table('penugasan_peserta')->insert([
                        'penugasan_id' => $penugasan->id,
                        'peserta_id' => $pesertaId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        // Optionally clear the pivot table
        // DB::table('penugasan_peserta')->truncate();
    }
};
