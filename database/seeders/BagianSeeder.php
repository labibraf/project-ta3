<?php

namespace Database\Seeders;
use App\Models\Bagian;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BagianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bagian = ['Biro Tata Koordinasi', 'Unit OpSib', 'Divisi Pengamanan'];
        foreach ($bagian as $item) {
            Bagian::create([
                'nama_bagian' => $item,
            ]);
        }
    }
}
 