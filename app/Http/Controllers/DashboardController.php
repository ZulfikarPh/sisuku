<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Models\Article; // <-- Import model Article
use App\Models\Agenda;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Ambil data profil anggota
        $anggota = $user->anggota()->with(['rayon', 'kategoriAnggota'])->first();

        // Ambil 4 artikel terbaru
        $latestArticles = Article::where('status', 'published')
            ->where('published_at', '<=', now())
            ->with('category')
            ->latest('published_at')
            ->limit(4)
            ->get();

        // LOGIKA BARU: Ambil 5 agenda terdekat
    $upcomingAgendas = Agenda::where('start_time', '>=', now())
        ->orderBy('start_time', 'asc')
        ->limit(5)
        ->get();

    return Inertia::render('Dashboard', [
        'anggota' => $anggota,
        'latestArticles' => $latestArticles,
        'upcomingAgendas' => $upcomingAgendas, // <-- Kirim data agenda
    ]);

    }
}
