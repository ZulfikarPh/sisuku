<?php

namespace App\Filament\Resources\AnggotaResource\Pages;

use App\Filament\Resources\AnggotaResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components;

class ViewAnggota extends ViewRecord
{
    protected static string $resource = AnggotaResource::class;

    // Memberi judul pada halaman view
    public function getTitle(): string
    {
        return 'Detail Anggota';
    }

    // Menambahkan tombol "Edit" di pojok kanan atas halaman view
    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }

    // Mendefinisikan tampilan detail data menggunakan Infolist
    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                // Seksi untuk Informasi Pribadi
                Components\Section::make('Informasi Pribadi')
                    ->schema([
                        // Grid untuk menata foto dan data teks
                        Components\Grid::make(3)->schema([
                            // Menampilkan foto di kolom pertama
                            Components\ImageEntry::make('foto')
                                ->circular()
                                ->defaultImageUrl(url('/images/default-avatar.png'))
                                ->columnSpan(1),

                            // Data teks di dua kolom sisanya
                            Components\Group::make()->schema([
                                Components\TextEntry::make('nama'),
                                Components\TextEntry::make('nia')->label('NIA'),
                                Components\TextEntry::make('ttl')->label('Tempat, Tanggal Lahir'),
                                Components\TextEntry::make('jenis_kelamin'),
                                Components\TextEntry::make('email')->icon('heroicon-o-envelope'),
                                Components\TextEntry::make('no_hp')->label('Nomor HP')->icon('heroicon-o-phone'),
                            ])->columns(2)->columnSpan(2),
                        ]),
                        // Alamat ditampilkan di bawah dengan lebar penuh
                        Components\TextEntry::make('alamat')->columnSpanFull(),
                    ]),

                // Seksi untuk Informasi Keanggotaan
                Components\Section::make('Informasi Keanggotaan')
                    ->schema([
                        Components\TextEntry::make('rayon.name')->label('Fakultas (Rayon)')->badge(),
                        Components\TextEntry::make('prodi.nama_prodi')->label('Program Studi'),
                        Components\TextEntry::make('kategoriAnggota.nama_kategori')->label('Kategori Anggota')->badge()->color('success'),
                        Components\TextEntry::make('tahun_mapaba')->label('Tahun MAPABA'),
                        Components\TextEntry::make('minat_dan_bakat')->columnSpanFull(),
                    ])->columns(2),
            ]);
    }
}
