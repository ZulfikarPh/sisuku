<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use Barryvdh\DomPDF\Facade\Pdf;

class AnggotaProfileController extends Controller
{
    /**
     * Menampilkan form edit profil keanggotaan.
     */
    public function edit(Request $request): Response
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        return Inertia::render('Anggota/EditProfile', [
            'anggota' => $user->load('anggota.rayon', 'anggota.kategoriAnggota')->anggota,
            'status' => session('status'),
        ]);
    }

    /**
     * Update informasi profil keanggotaan.
     */
    public function update(Request $request): RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $anggota = $user->anggota;

        if (!$anggota) {
            abort(404, 'Data anggota tidak ditemukan.');
        }

        $validated = $request->validate([
            'no_hp' => ['nullable', 'string', 'max:20'],
            'alamat' => ['nullable', 'string'],
            'minat_dan_bakat' => ['nullable', 'string'],
            'foto' => ['nullable', 'image', 'max:1024'],
        ]);

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('foto-anggota', 'public');
        }

        $anggota->update($validated);

        return Redirect::route('anggota.profile.edit')->with('status', 'profile-anggota-updated');
    }

    /**
     * Download kartu anggota sebagai PDF (ukuran seperti KTP).
     */
    public function downloadCard()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $anggota = $user->load('anggota.rayon')->anggota;

        if (!$anggota) {
            abort(404, 'Profil anggota tidak ditemukan.');
        }

        try {
            $pdf = Pdf::loadView('pdf.kartu-anggota', compact('anggota'));
            $pdf->setPaper([0, 0, 242.6, 153], 'landscape'); // Ukuran KTP

            return $pdf->stream('KTA - ' . $anggota->nama . '.pdf');
        } catch (\Exception $e) {
            return response()->view('errors.custom', [
                'message' => 'Gagal membuat kartu anggota. Pastikan semua data dan view valid.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
