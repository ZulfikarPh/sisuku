<?php

namespace App\Exports;

use App\Models\Anggota;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class AnggotaExport implements FromCollection, WithHeadings, WithMapping, WithDrawings, WithColumnWidths, WithEvents
{
    protected Collection $records;

    public function __construct(?Collection $records = null)
    {
        $this->records = $records ?? Anggota::with(['rayon', 'prodi', 'kategoriAnggota'])->get();
    }

    public function collection()
    {
        return $this->records;
    }

    public function headings(): array
    {
        return ['Foto', 'Nama Lengkap', 'NIA', 'Email', 'No HP', 'Fakultas (Rayon)', 'Program Studi', 'Kategori', 'Tahun MAPABA'];
    }

    public function map($anggota): array
    {
        return [
            '', // Kolom A untuk gambar
            $anggota->nama,
            $anggota->nia,
            $anggota->email,
            $anggota->no_hp,
            $anggota->rayon?->name ?? 'N/A',
            $anggota->prodi?->nama_prodi ?? 'N/A',
            $anggota->kategoriAnggota?->nama_kategori ?? 'N/A',
            $anggota->tahun_mapaba,
        ];
    }

     public function drawings()
    {
        // Log awal untuk menandai proses dimulai
        Log::info('Memulai proses ekspor gambar untuk ' . $this->records->count() . ' record.');

        $drawings = [];
        foreach ($this->records as $index => $anggota) {
            $fotoPath = $anggota->foto; // Ambil path foto dari record

            // Log untuk setiap anggota
            Log::info('Mengecek Anggota: ' . $anggota->nama . ' | Path Foto dari DB: ' . ($fotoPath ?? 'KOSONG'));

            if ($fotoPath) {
                $fullPath = public_path('storage/' . $fotoPath);

                // Cek apakah file benar-benar ada di path tersebut
                if (file_exists($fullPath)) {
                    Log::info('--> SUKSES: File ditemukan di: ' . $fullPath);

                    $drawing = new Drawing();
                    $drawing->setName($anggota->nama);
                    $drawing->setDescription('Foto profil ' . $anggota->nama);
                    $drawing->setPath($fullPath);
                    $drawing->setHeight(50);
                    $drawing->setOffsetX(5);
                    $drawing->setOffsetY(5);
                    $drawing->setCoordinates('A' . ($index + 2));

                    $drawings[] = $drawing;
                } else {
                    // Log jika file tidak ditemukan di server
                    Log::warning('--> GAGAL: File TIDAK DITEMUKAN di path: ' . $fullPath);
                }
            }
        }

        Log::info('Proses ekspor gambar selesai. Total gambar yang disisipkan: ' . count($drawings));

        return $drawings;
    }

    public function columnWidths(): array
    {
        return [ 'A' => 10, 'B' => 30, 'C' => 20, 'D' => 30, 'E' => 20, 'F' => 25, 'G' => 30, 'H' => 15, 'I' => 15 ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->getDefaultRowDimension()->setRowHeight(45);
                $event->sheet->getRowDimension(1)->setRowHeight(20); // Atur tinggi header
            },
        ];
    }
}
