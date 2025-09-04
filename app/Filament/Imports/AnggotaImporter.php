<?php

namespace App\Filament\Imports;

use App\Models\Anggota; // <-- Sesuaikan dengan path model Anda
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Models\Import;

class AnggotaImporter extends Importer
{
    protected static ?string $model = Anggota::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('nama')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('email')
                ->requiredMapping()
                ->rules(['required', 'email', 'unique:anggotas,email']), // <-- Sesuaikan 'anggotas' dengan nama tabel Anda
            ImportColumn::make('nomor_telepon')
                ->label('Nomor Telepon'), // Label opsional jika nama kolom di Excel berbeda
            ImportColumn::make('alamat'),
            // Tambahkan kolom lain yang ingin Anda impor di sini
        ];
    }

    public function resolveRecord(): ?Anggota
    {
        // Logika untuk mencari data yang sudah ada (opsional, untuk update)
        // return Anggota::firstOrNew([
        //     // Mencari anggota berdasarkan email
        //     'email' => $this->data['email'],
        // ]);

        return new Anggota(); // Selalu buat data baru
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Impor anggota Anda telah selesai dan ' . number_format($import->successful_rows) . ' baris berhasil diimpor.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' baris gagal diimpor.';
        }

        return $body;
    }
}
