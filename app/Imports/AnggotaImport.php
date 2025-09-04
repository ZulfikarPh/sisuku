<?php

namespace App\Imports;

use App\Models\Anggota;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class AnggotaImport implements ToModel, WithHeadingRow, WithValidation, WithBatchInserts, WithChunkReading
{
    /**
    * Method ini akan dijalankan untuk setiap baris di dalam file Excel.
    * Ia akan membuat record Anggota baru berdasarkan data baris tersebut.
    * @param array $row
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Anggota([
            'nama'                  => $row['nama'],
            'nia'                   => $row['nia'],
            'email'                 => $row['email'],
            'no_hp'                 => $row['no_hp'],
            'ttl'                   => $row['ttl'],
            'jenis_kelamin'         => $row['jenis_kelamin'],
            'alamat'                => $row['alamat'],
            'tahun_mapaba'          => $row['tahun_mapaba'],
            'minat_dan_bakat'       => $row['minat_dan_bakat'],
            'rayon_id'              => $row['rayon_id'],
            'prodi_id'              => $row['prodi_id'],
            'kategori_anggota_id'   => $row['kategori_anggota_id'],
        ]);
    }

    /**
     * Mendefinisikan aturan validasi untuk setiap baris di file Excel.
     * Jika ada baris yang tidak valid, proses impor untuk baris itu akan gagal
     * dan admin akan diberi tahu.
     */
    public function rules(): array
    {
        return [
            'nama' => ['required', 'string'],
            'nia' => ['required', 'string', 'unique:anggotas,nia'],
            'email' => ['required', 'email', 'unique:anggotas,email'],
            'rayon_id' => ['required', 'integer', 'exists:rayons,id'],
            'prodi_id' => ['required', 'integer', 'exists:prodis,id'],
            'kategori_anggota_id' => ['required', 'integer', 'exists:kategori_anggotas,id'],

            // Validasi untuk kolom lain (opsional, bisa disesuaikan)
            'tahun_mapaba' => ['required', 'integer'],
            'jenis_kelamin' => ['required', 'in:Laki-laki,Perempuan'],
        ];
    }

    /**
     * Optimasi: Menyimpan data ke database dalam kelompok (batch)
     * agar lebih cepat untuk file berukuran besar.
     */
    public function batchSize(): int
    {
        return 100; // Simpan per 100 baris
    }

    /**
     * Optimasi: Membaca file Excel dalam potongan (chunk)
     * agar tidak memakan banyak memori.
     */
    public function chunkSize(): int
    {
        return 100; // Baca per 100 baris
    }
}
