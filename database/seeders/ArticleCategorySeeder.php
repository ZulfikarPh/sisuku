<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ArticleCategory;
use Illuminate\Support\Str;

class ArticleCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Berita',
            'Opini',
            'Wacana',
            'Esai',
            'Ke-PMII-an',
            'Rilis Aksi',
        ];

        foreach ($categories as $categoryName) {
            ArticleCategory::updateOrCreate(
                ['name' => $categoryName],
                ['slug' => Str::slug($categoryName)]
            );
        }
    }
}
