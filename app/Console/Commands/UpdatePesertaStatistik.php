<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Peserta;

class UpdatePesertaStatistik extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'peserta:update-statistik';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update statistik peserta untuk menghitung tugas individu dan divisi';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Memulai update statistik peserta...');

        $peserta = Peserta::all();
        $totalPeserta = $peserta->count();

        $this->info("Ditemukan {$totalPeserta} peserta");

        $bar = $this->output->createProgressBar($totalPeserta);
        $bar->start();

        foreach ($peserta as $p) {
            // Update waktu tugas tercapai
            $waktuLama = $p->waktu_tugas_tercapai;
            $p->updateWaktuTugasTercapai();
            $p->refresh();

            // Test accessor baru
            $totalTugas = $p->total_tugas;
            $tugasSelesai = $p->tugas_selesai;

            $this->line('');
            $this->line("Peserta: {$p->nama_lengkap}");
            $this->line("  Bagian: " . ($p->bagian ? $p->bagian->nama_bagian : 'N/A'));
            $this->line("  Waktu tercapai: {$waktuLama} jam -> {$p->waktu_tugas_tercapai} jam");
            $this->line("  Total tugas: {$totalTugas}");
            $this->line("  Tugas selesai: {$tugasSelesai}");
            $this->line("  -------------------------");

            $bar->advance();
        }

        $bar->finish();
        $this->info('');
        $this->info('Update statistik peserta selesai!');

        return 0;
    }
}
