<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tag;
use Illuminate\Support\Str;

class TagSeeder extends Seeder
{
    public function run(): void
    {
        $tags = [
            'Kaderisasi',
            'Ahlussunnah wal Jamaah',
            'NDP PMII',
            'Filsafat',
            'Ekonomi Syariah',
            'Politik Kampus',
            'Gender',
            'Advokasi',
        ];

        foreach ($tags as $tagName) {
            Tag::updateOrCreate(
                ['name' => $tagName],
                ['slug' => Str::slug($tagName)]
            );
        }
    }
}
