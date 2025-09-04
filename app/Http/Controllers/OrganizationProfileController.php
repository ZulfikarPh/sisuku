<?php

namespace App\Http\Controllers;

use App\Models\Rayon;
use App\Models\Article;
use App\Models\Anggota; // <-- Tambahkan use statement ini
use Illuminate\Support\Facades\DB; // <-- Dan ini
use Inertia\Inertia;

class OrganizationProfileController extends Controller
{
    public function showRayon(Rayon $rayon)
    {
        $rayon->load('profile');

        $articles = Article::where('rayon_id', $rayon->id)
                            ->where('status', 'published')
                            ->where('published_at', '<=', now())
                            ->with('category')
                            ->latest('published_at')
                            ->paginate(6)
                            ->withQueryString();

        // [BARU] Query untuk mengambil statistik anggota HANYA untuk rayon ini
        $memberStats = Anggota::query()
                            ->where('rayon_id', $rayon->id) // Filter utama berdasarkan rayon_id
                            ->join('kategori_anggotas', 'anggotas.kategori_anggota_id', '=', 'kategori_anggotas.id')
                            ->select('kategori_anggotas.nama_kategori', DB::raw('count(*) as total'))
                            ->groupBy('kategori_anggotas.nama_kategori')
                            ->get();

        // [DIUBAH] Tambahkan 'memberStats' ke data yang dikirim
        return Inertia::render('Profil/ShowRayon', [
            'rayon' => $rayon,
            'articles' => $articles,
            'memberStats' => $memberStats, // Kirim data statistik ke frontend
        ]);
    }
}
