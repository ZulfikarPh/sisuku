<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Rayon;
use App\Models\Prodi;

class ProdiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // [DISESUAIKAN] Struktur data kini menggunakan daftar prodi dari gambar terbaru.
        $rayonDanProdi = [
            'Tarbiyah' => [
                'Pendidikan Agama Islam',
                'Pendidikan Bahasa Arab',
                'Pendidikan Guru Madrasah Ibtidaiyah',
                'Pendidikan Islam Anak Usia Dini',
                'Tadris Bahasa Inggris',
                'Tadris Matematika',
                'Tadris IPA',
                'Tadris Biologi',
                'Tadris IPS',
                'Bimbingan dan Konseling Pendidikan Islam',
            ],
            'Syariah' => [
                'Hukum Ekonomi Syariah',
                'Hukum Keluarga Islam',
            ],
            'Ushuluddin' => [
                'Ilmu Al-Qur`an dan Tafsir',
                'Ilmu Hadis',
                'Tasawuf dan Psikoterapi',
                'Aqidah dan Filsafat Islam',
            ],
            'REBI' => [ // Menggunakan 'REBI' sesuai database Anda
                'Ekonomi Syariah',
                'Perbankan Syariah',
                'Manajemen Bisnis Syariah',
                'Akuntansi Syariah',
                'Manajemen Zakat Wakaf',
            ],
            'Dakwah' => [
                'Manajemen Dakwah',
                'Bimbingan Konseling Islam',
                'Pengembangan Masyarakat Islam',
                'Pemikiran Politik Islam',
                'Komunikasi Penyiaran Islam',
            ]
            // Catatan: Fakultas Dakwah tidak ada di gambar referensi baru,
            // jadi untuk sementara tidak dimasukkan ke dalam seeder ini.
        ];

        // Looping untuk setiap rayon dari data di atas
        foreach ($rayonDanProdi as $namaRayon => $daftarProdi) {
            // Cari Rayon di database berdasarkan nama
            $rayon = Rayon::where('name', $namaRayon)->first();

            // Jika Rayon ditemukan, lanjutkan membuat data Prodi
            if ($rayon) {
                // Looping untuk setiap prodi di dalam rayon tersebut
                foreach ($daftarProdi as $namaProdi) {
                    // Gunakan updateOrCreate untuk mencegah duplikasi data
                    Prodi::updateOrCreate(
                        [
                            'rayon_id' => $rayon->id,
                            'nama_prodi' => $namaProdi,
                        ],
                        []
                    );
                }
            }
        }
    }
}
