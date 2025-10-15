<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Testing Penugasan model:\n";

$penugasan = \App\Models\Penugasan::where('kategori', 'Divisi')->first();

if ($penugasan) {
    echo "Penugasan found: " . $penugasan->judul_tugas . "\n";
    echo "Bagian ID: " . $penugasan->bagian_id . "\n";
    
    $pesertas = $penugasan->pesertas()->get();
    echo "Peserta count: " . $pesertas->count() . "\n";
    
    foreach ($pesertas as $peserta) {
        echo "Peserta: " . $peserta->nama_lengkap . " - Target: " . ($peserta->target_waktu_tugas ?? 'null') . "\n";
    }
} else {
    echo "No Divisi penugasan found\n";
}