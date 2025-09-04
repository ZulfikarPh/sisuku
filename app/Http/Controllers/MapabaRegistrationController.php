<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Prodi;
use App\Models\Rayon;
use Inertia\Response;
use App\Models\MapabaRegistration;
use App\Http\Requests\StoreMapabaRegistrationRequest;
use Illuminate\Support\Facades\Storage; // Digunakan untuk upload/hapus file
use Illuminate\Support\Facades\Log;     // Digunakan untuk logging error
use Illuminate\Support\Facades\DB;      // Digunakan untuk transaksi database
use Carbon\Carbon;                      // Digunakan untuk tahun otomatis
// Tidak perlu mengimpor Illuminate\Http\Request di sini karena StoreMapabaRegistrationRequest sudah mencakup fungsionalitasnya

class MapabaRegistrationController extends Controller
{
    /**
     * Menampilkan halaman form pendaftaran MAPABA.
     * Mengirimkan data rayon dan prodi ke tampilan React.
     *
     * @return \Inertia\Response
     */
    public function create(): Response
    {
        // Mengambil daftar Rayon (kolom 'id' dan 'name').
        // Asumsi nama kolom untuk nama Rayon di tabel 'rayons' adalah 'name'.
        $rayons = Rayon::get(['id', 'name']);

        // Mengambil daftar Prodi (kolom 'id', 'nama_prodi', dan 'rayon_id').
        // 'rayon_id' penting untuk filtering prodi dinamis di sisi frontend React.
        // Asumsi nama kolom untuk nama Prodi di tabel 'prodis' adalah 'nama_prodi'.
        $prodis = Prodi::get(['id', 'nama_prodi', 'rayon_id']);

        return Inertia::render('MAPABA/Register', [
            'rayons' => $rayons,
            'prodis' => $prodis,
            // Mengirimkan pesan flash dari session (misalnya 'success' atau 'error') ke props Inertia.
            'flash' => session('flash')
        ]);
    }

    /**
     * Menyimpan data pendaftar baru dari form MAPABA.
     * Data divalidasi menggunakan StoreMapabaRegistrationRequest.
     * File diunggah ke storage dan path-nya disimpan di database.
     *
     * @param  \App\Http\Requests\StoreMapabaRegistrationRequest  $request  // Hanya gunakan $request ini, sudah cukup
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreMapabaRegistrationRequest $request) // Tidak ada parameter kedua $httpRequest
    {
        // Mengambil data yang sudah divalidasi dari FormRequest.
        $validatedData = $request->validated();

        // --- Logika Khusus untuk Kolom yang Diisi Otomatis atau Dihilangkan dari Form ---

        // Mengisi tahun_mapaba secara otomatis dengan tahun saat ini.
        // Ini akan menimpa nilai 'tahun_mapaba' yang mungkin datang dari request jika ada.
        $validatedData['tahun_mapaba'] = Carbon::now()->year;

        // Mengatur kolom 'minat_dan_bakat' menjadi null karena tidak lagi ada di form React.
        // Asumsi kolom ini nullable di tabel 'mapaba_registrations'.
        $validatedData['minat_dan_bakat'] = null;

        // Memastikan kolom-kolom yang bersifat nullable di database disetel sebagai null
        // jika nilai yang diterima dari form React kosong atau tidak ada.
        // Ini mencegah error 'Column cannot be null'.
        $nullableFieldsFromReact = ['ttl', 'jenis_kelamin', 'alamat'];
        foreach ($nullableFieldsFromReact as $field) {
            // Memeriksa jika field tidak ada di validatedData (artinya tidak dikirim dari form)
            // atau jika nilai yang dikirim adalah string kosong.
            if (!isset($validatedData[$field]) || $validatedData[$field] === '') {
                $validatedData[$field] = null;
            }
        }

        // Status awal pendaftaran selalu 'pending' saat pertama kali disimpan.
        $validatedData['status'] = 'pending';

        // --- Proses Upload File ---
        // Mengunggah file ke direktori yang ditentukan di storage/app/public/.
        // Menggunakan $request->file() karena $request (sebagai FormRequest) memiliki method ini.
        // Pastikan Anda sudah menjalankan 'php artisan storage:link'.
        try {
            $validatedData['bukti_follow_path'] = $request->file('bukti_follow')->store('mapaba-docs/bukti-follow', 'public');
            $validatedData['foto_ktm_path'] = $request->file('foto_ktm')->store('mapaba-docs/foto-ktm', 'public');
            $validatedData['bukti_pembayaran_path'] = $request->file('bukti_pembayaran')->store('mapaba-docs/bukti-bayar', 'public');
        } catch (\Exception $e) {
            // Jika ada masalah saat upload file, catat error dan kembalikan ke form
            Log::error('Mapaba Registration File Upload Error: ' . $e->getMessage(), ['exception' => $e, 'request_files' => $request->allFiles()]); // $request->allFiles()
            // Mengembalikan dengan input sebelumnya kecuali file-file
            return redirect()->back()->with('error', 'Gagal mengunggah file. Pastikan format dan ukuran file sesuai.')->withInput($request->except(['bukti_follow', 'foto_ktm', 'bukti_pembayaran'])); // $request->except()
        }

        // --- Menyimpan Data ke Database dengan Transaksi ---
        // Menggunakan transaksi database untuk memastikan operasi atomik:
        // Jika ada bagian dari proses penyimpanan gagal, semua perubahan (termasuk upload file)
        // akan di-rollback untuk menjaga konsistensi data.
        try {
            DB::beginTransaction(); // Memulai transaksi

            // INI ADALAH BARIS KRUSIAL: Membuat entri baru di tabel mapaba_registrations.
            // Pastikan TIDAK ADA PANGGILAN 'Anggota::create(...)' di sini.
            // Anggota::create() hanya dipanggil saat verifikasi di Filament.
            MapabaRegistration::create($validatedData);

            DB::commit(); // Komit transaksi jika semuanya berhasil

            // Mengarahkan pengguna kembali ke form pendaftaran dengan pesan sukses.
            return redirect()->route('pendaftaran.mapaba.create')->with('success', 'Pendaftaran Anda telah berhasil dikirim! Silakan tunggu informasi selanjutnya.');

        } catch (\Exception $e) {
            DB::rollBack(); // Rollback transaksi jika terjadi kesalahan database

            // Hapus file yang sudah diupload jika database insertion gagal,
            // agar tidak ada file 'yatim' di storage.
            Storage::disk('public')->delete([
                $validatedData['bukti_follow_path'],
                $validatedData['foto_ktm_path'],
                $validatedData['bukti_pembayaran_path']
            ]);

            // Catat error ke log Laravel untuk debugging.
            Log::error('Mapaba Registration Database Store Error: ' . $e->getMessage(), [
                'exception' => $e,
                'request_data' => $request->all(), // $request->all()
                'validated_data_before_create' => $validatedData
            ]);

            // Mengarahkan pengguna kembali dengan pesan error dan data input sebelumnya (kecuali file).
            return redirect()->back()->with('error', 'Terjadi kesalahan saat pendaftaran. Mohon coba lagi.')->withInput($request->except(['bukti_follow', 'foto_ktm', 'bukti_pembayaran'])); // $request->except()
        }
    }
}
