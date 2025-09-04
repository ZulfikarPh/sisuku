<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon; // Untuk validasi max tahun

class StoreMapabaRegistrationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'nim' => ['required', 'string', 'max:255', 'unique:mapaba_registrations,nim'],
            'email' => ['required', 'email', 'max:255', 'unique:mapaba_registrations,email'],
            'no_hp' => ['required', 'string', 'max:20'],
            'rayon_id' => ['required', 'exists:rayons,id'],
            'prodi_id' => ['required', 'exists:prodis,id'],

            'ttl' => ['nullable', 'string', 'max:255'],
            'jenis_kelamin' => ['nullable', 'in:Laki-Laki,Perempuan'],
            'alamat' => ['nullable', 'string'],
            // 'tahun_mapaba' TIDAK ADA RULE DI SINI, KARENA DIISI OTOMATIS DI BACKEND
            // 'minat_dan_bakat' TIDAK ADA RULE DI SINI

            // Kolom file upload DIKEMBALIKAN
            'bukti_follow' => ['required', 'image', 'max:2048'], // 'image' hanya untuk gambar, 'mimes:pdf,jpg,png' lebih spesifik
            'foto_ktm' => ['required', 'image', 'max:2048'],
            'bukti_pembayaran' => ['required', 'image', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
            'nim.unique' => 'NIM ini sudah terdaftar.',
            'email.unique' => 'Email ini sudah terdaftar.',
            'jenis_kelamin.in' => 'Jenis kelamin harus Laki-Laki atau Perempuan.',
            'bukti_follow.required' => 'Bukti follow IG PMII wajib diunggah.',
            'bukti_follow.image' => 'Bukti follow harus berupa gambar (JPG, PNG, GIF, SVG).', // Sesuaikan
            'bukti_follow.max' => 'Ukuran bukti follow maksimal 2MB.',
            'foto_ktm.required' => 'Foto KTM wajib diunggah.',
            'foto_ktm.image' => 'Foto KTM harus berupa gambar.',
            'foto_ktm.max' => 'Ukuran foto KTM maksimal 2MB.',
            'bukti_pembayaran.required' => 'Bukti pembayaran wajib diunggah.',
            'bukti_pembayaran.image' => 'Bukti pembayaran harus berupa gambar.',
            'bukti_pembayaran.max' => 'Ukuran bukti pembayaran maksimal 2MB.',
        ];
    }
}
