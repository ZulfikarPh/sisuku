<?php

namespace App\Filament\Resources\AnggotaResource\Pages;

use App\Filament\Imports\AnggotaImporter;
use App\Filament\Resources\AnggotaResource;
use App\Models\Rayon;
use Filament\Actions;
use Filament\Actions\ImportAction;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage; // <-- PENTING: Tambahkan ini untuk URL Foto
use pxlrbt\FilamentExcel\Actions\Pages\ExportAction;
use pxlrbt\FilamentExcel\Columns\Column; // <-- PENTING: Tambahkan ini untuk definisi kolom
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class ListAnggotas extends ListRecords
{
    protected static string $resource = AnggotaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Tombol Buat Anggota Baru
            Actions\CreateAction::make(),

            // Tombol Impor
            ImportAction::make()
                ->importer(AnggotaImporter::class),

            // Tombol Ekspor Lengkap
            ExportAction::make()
                ->exports([
                    ExcelExport::make('download')
                        ->withFilename('Daftar Lengkap Anggota - ' . date('Y-m-d'))
                        ->withColumns([
                            Column::make('nama')->heading('Nama Lengkap'),
                            Column::make('nia')->heading('NIA'),
                            Column::make('email')->heading('Email'),
                            Column::make('no_hp')->heading('Nomor HP'),
                            Column::make('ttl')->heading('Tempat, Tanggal Lahir'),
                            Column::make('jenis_kelamin')->heading('Jenis Kelamin'),
                            Column::make('alamat')->heading('Alamat Lengkap'),
                            Column::make('rayon.name')->heading('Fakultas (Rayon)'),
                            Column::make('prodi.nama_prodi')->heading('Program Studi'),
                            Column::make('tahun_mapaba')->heading('Tahun MAPABA'),
                            Column::make('kategoriAnggota.nama_kategori')->heading('Kategori Anggota'),
                            Column::make('minat_dan_bakat')->heading('Minat dan Bakat'),
                            Column::make('foto')
                                ->heading('Link Foto')
                                ->getStateUsing(fn ($record) => $record->foto ? Storage::url($record->foto) : ''), // <-- Ini yang benar
                            Column::make('created_at')->heading('Tanggal Bergabung'),
                            Column::make('updated_at')->heading('Terakhir Diupdate'),
                        ])
                ]),
        ];
    }

    protected function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Ambil ID untuk rayon 'Komisariat' dari database
        $komisariatRayonId = Rayon::where('name', 'Komisariat')->value('id');

        // Cek langsung ke rayon_id user, bukan rolenya.
        if ($user->rayon_id != $komisariatRayonId) {
            $query->where('rayon_id', $user->rayon_id);
        }

        return $query;
    }
}
