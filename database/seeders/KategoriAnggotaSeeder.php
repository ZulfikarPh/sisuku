<?php

namespace Database\Seeders;

use App\Models\KategoriAnggota;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KategoriAnggotaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Data kategori yang ingin kita masukkan
        $kategori = [
            'Calon Anggota',
            'Anggota',
            'Kader',
            'Alumni',
        ];

        foreach ($kategori as $item) {
            // [DIPERBAIKI] Gunakan 'nama_kategori' sesuai dengan nama kolom di database Anda
            KategoriAnggota::updateOrCreate(['nama_kategori' => $item]);
        }
    }
}
