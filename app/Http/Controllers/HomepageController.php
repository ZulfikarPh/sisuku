<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\KomisariatProfile;
use App\Models\Testimoni;
use App\Models\Anggota;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Illuminate\Support\Facades\Cache;

class HomepageController extends Controller
{
    public function __invoke()
    {
        // Mengambil semua data dalam satu query jika memungkinkan atau secara efisien
        $komisariatProfile = KomisariatProfile::first();

        // Ambil 3 artikel terbaru untuk ditampilkan di homepage
        $latestArticles = Article::where('status', 'published')
            ->where('published_at', '<=', now())
            ->with(['category', 'rayon'])
            ->latest('published_at')
            ->limit(3)
            ->get();

        // Ambil 3 testimoni yang visible
        $testimonials = Testimoni::where('is_visible', true)
            ->orderBy('order')
            ->limit(3)
            ->get();

        // Hitung statistik anggota
        $memberStats = Anggota::query()
            ->join('kategori_anggotas', 'anggotas.kategori_anggota_id', '=', 'kategori_anggotas.id')
            ->select('kategori_anggotas.nama_kategori', DB::raw('count(*) as total'))
            ->groupBy('kategori_anggotas.nama_kategori')
            ->get();

        return Inertia::render('Homepage', [
            'komisariatProfile' => $komisariatProfile,
            'articles' => $latestArticles,
            'testimonials' => $testimonials,
            'memberStats' => $memberStats,
        ]);
    }
}
