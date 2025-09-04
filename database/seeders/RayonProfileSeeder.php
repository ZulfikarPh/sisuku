<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Rayon;
use App\Models\RayonProfile;

class RayonProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil semua rayon yang ada di database
        $rayons = Rayon::where('name', '!=', 'Komisariat')->get();

        // Loop untuk setiap rayon
        foreach ($rayons as $rayon) {
            // Gunakan updateOrCreate untuk membuat profil jika belum ada
            RayonProfile::updateOrCreate(
                [
                    'rayon_id' => $rayon->id, // Kunci untuk mencari record yang ada
                ],
                [
                    // Data placeholder yang akan diisi atau di-update
                    // Sebagian besar kita biarkan null agar bisa diisi dari admin panel
                    'jargon' => 'Jargon Inspiratif Rayon ' . $rayon->name,
                    'description' => '<p>Deskripsi awal untuk profil Rayon ' . $rayon->name . '. Silakan edit dan lengkapi melalui panel admin.</p>',
                    'email' => strtolower($rayon->slug) . '@sisuku.test',
                    // Biarkan field lain null agar bisa diisi kemudian
                ]
            );
        }
    }
}
