<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Rayon;
use Illuminate\Support\Str;

class RayonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rayons = [
            'Tarbiyah', 'Ushuluddin', 'Syariah', 'Dakwah', 'REBI', 'Komisariat'
        ];

        foreach ($rayons as $rayonName) {
            // Cek berdasarkan 'name', jika belum ada, baru buat dengan data lengkapnya.
            Rayon::firstOrCreate(
                ['name' => $rayonName],
                ['slug' => Str::slug($rayonName)]
            );
        }
    }
}
