<?php

namespace App\Filament\Widgets;

use App\Models\Article; // <-- UBAH DARI "Post" MENJADI "Article"
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn; // Opsional, jika ada status artikel

class LatestPosts extends BaseWidget
{
    protected static ?int $sort = 2; // Urutan widget di dashboard, setelah statistik anggota

    protected static ?string $heading = 'Publikasi Berita Terbaru'; // Judul widget

    protected function getTableQuery(): Builder
    {
        // <-- UBAH DARI "Post::query()" MENJADI "Article::query()"
        return Article::query()->latest()->limit(5); // Ambil 5 artikel terbaru
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('title') // Kolom judul artikel (asumsi nama kolomnya 'title')
                ->label('Judul Artikel')
                ->searchable()
                ->sortable(),
            // Jika ada relasi dengan user/author pada model Article Anda:
            TextColumn::make('author.name') // Contoh: 'author' adalah relasi, 'name' adalah kolom di tabel author
                ->label('Penulis')
                ->searchable()
                ->sortable(),
            // Contoh jika ada kolom status pada model Article Anda:
            // BadgeColumn::make('status')
            //     ->colors([
            //         'danger' => 'draft',
            //         'success' => 'published',
            //     ])
            //     ->label('Status'),
            TextColumn::make('published_at') // Kolom tanggal publikasi (asumsi nama kolomnya 'published_at')
                ->label('Tanggal Publikasi')
                ->dateTime('d M Y H:i')
                ->sortable(),
        ];
    }
}
