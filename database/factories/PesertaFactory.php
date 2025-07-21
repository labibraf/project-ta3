<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Peserta>
 */
class PesertaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
         // Data untuk membangun nama instansi (universitas/sekolah)
        $city = $this->faker->city();
        $randomWords = $this->faker->unique()->words(rand(1, 3), true);

        // Daftar jurusan yang lebih relevan
        $jurusanRelevan = [
            'Teknik Informatika',
            'Sistem Informasi',
            'Ilmu Komputer',
            'Manajemen Informatika',
            'Teknik Elektro',
            'Teknik Mesin',
            'Teknik Sipil',
            'Arsitektur',
            'Akuntansi',
            'Manajemen',
            'Ilmu Ekonomi',
            'Hukum',
            'Ilmu Komunikasi',
            'Psikologi',
            'Kedokteran',
            'Farmasi',
            'Matematika',
            'Fisika',
            'Kimia',
            'Biologi',
            'Desain Komunikasi Visual',
            'Seni Rupa',
            'Pendidikan Bahasa Inggris',
            'Pendidikan Matematika',
            'Hubungan Internasional',
            'Administrasi Publik',
            'Ilmu Pemerintahan',
            'Agroteknologi',
            'Teknologi Pangan',
            'Statistika',
            'Geografi',
            'Geologi',
            'Teknik Industri',
            'Pariwisata',
            'Ilmu Gizi',
            'Kesehatan Masyarakat',
            'Ilmu Perpustakaan',
            'Sosiologi',
            'Antropologi'
        ];

        return [
            'nama_lengkap' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'no_telepon' => $this->faker->phoneNumber(),
            'alamat' => $this->faker->address(),
            'jenis_kelamin' => $this->faker->randomElement(['Laki-laki', 'Perempuan']),

            // Penyesuaian asal_instansi
            'asal_instansi' => $this->faker->randomElement([
                'Universitas ' . $city,
                'Institut Teknologi ' . $city,
                $randomWords . ' University',
                'Politeknik Negeri ' . $city,
                'SMA Negeri ' . $this->faker->numberBetween(1, 200) . ' ' . $city,
                'SMK ' . $this->faker->randomElement(['Negeri', 'Swasta']) . ' ' . $this->faker->words(1, true) . ' ' . $city,
                'Universitas ' . $this->faker->lastName()
            ]),

            // Penyesuaian jurusan
            'jurusan' => $this->faker->randomElement($jurusanRelevan),

            'nomor_identitas' => (string) $this->faker->unique()->randomNumber(9, true), // Menjadi string, 9 digit unik
            'tipe_magang' => $this->faker->randomElement(['Mandiri', 'Pemerintah', 'Undangan']),
            'tanggal_mulai_magang' => $this->faker->dateTimeBetween('-3 months', '+3 months')->format('Y-m-d'), // Tanggal mulai realistis
            'tanggal_selesai_magang' => $this->faker->dateTimeBetween('+6 months', '+1 year')->format('Y-m-d'), // Tanggal selesai realistis
        ];
    }
}
