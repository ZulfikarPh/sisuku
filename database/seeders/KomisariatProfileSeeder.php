<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\KomisariatProfile;
class KomisariatProfileSeeder extends Seeder
{
    public function run(): void
    {
        // Buat satu baris data kosong
        KomisariatProfile::create([]);
    }
}
