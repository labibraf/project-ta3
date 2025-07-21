<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\Peserta;

class PesertaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Peserta::factory(10)->create();
        // $faker = Faker::create('id_ID');
        // for($i=0;$i<15;$i++){
        //     Peserta::create([
        //         'nama_lengkap' => $faker->name(),
        //         'email' => $faker->unique()->safeEmail(),
        //         'no_telepon' => $faker->phoneNumber(),
        //         'alamat' => $faker->address(),
        //         'jenis_kelamin' => $faker->randomElement(['Laki-laki', 'Perempuan']),
        //         'asal_instansi' => $faker->company(),
        //         'jurusan' => $faker->jobTitle(),
        //         'nomor_identitas' => $faker-> randomNumber(6), // e.g., NIK, KTP, etc.
        //         'tipe_magang' => $faker->randomElement(['Mandiri', 'Pemerintah', 'Undangan']),
        //         'tanggal_mulai_magang' => $faker->dateTimeBetween('-1 year', '+1 year'),
        //         'tanggal_selesai_magang' => $faker->dateTimeBetween('+1 month', '+2 years'),
        //     ]);
        // }
    }
}
