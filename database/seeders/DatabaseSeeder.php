<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            /**
             * -------------------------------------------
             * SEEDER DATA MASTER (FONDASI)
             * Dijalankan pertama karena tidak punya ketergantungan.
             * -------------------------------------------
             */
            RayonSeeder::class,
            KategoriAnggotaSeeder::class,
            ArticleCategorySeeder::class, // <-- Seeder baru ditambahkan
            TagSeeder::class,             // <-- Seeder baru ditambahkan

            /**
             * -------------------------------------------
             * SEEDER DATA RELASI & PROFIL
             * Dijalankan setelah data master ada.
             * -------------------------------------------
             */
            ProdiSeeder::class, // Bergantung pada RayonSeeder
            UserSeeder::class,  // Untuk user admin, dll.
            KomisariatProfileSeeder::class,
            RayonProfileSeeder::class, // Bergantung pada RayonSeeder

            /**
             * -------------------------------------------
             * SEEDER DATA CONTOH (JIKA ADA)
             * Dijalankan paling akhir.
             * -------------------------------------------
             */
            // AnggotaSeeder::class, // Bergantung pada Rayon, Prodi, Kategori, dll.
            // ArticleSeeder::class, // Bergantung pada User, Kategori, Tag, dll.
        ]);
    }
}
