<?php

namespace App\Providers;

use Inertia\Inertia;
use App\Models\Rayon;
use App\Models\Anggota;
use App\Models\Article;
use App\Models\KomisariatProfile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Builder;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
         Inertia::share([
        // ... (data 'rayons' Anda)
        'komisariatProfile' => fn () => KomisariatProfile::first(),
    ]);
        // =================================================================
        // BAGIAN 1: BERBAGI DATA GLOBAL KE FRONTEND
        // Kode ini akan selalu berjalan di setiap halaman.
        // =================================================================
        Inertia::share([
            // Membuat variabel 'rayons' yang bisa diakses di semua halaman frontend
            'rayons' => fn () => Rayon::where('name', '!=', 'Komisariat')->get(),
        ]);


        // =================================================================
        // BAGIAN 2: LOGIKA UNTUK MODEL ANGGOTA
        // =================================================================

        // Aturan Global Scope: Berjalan setiap kali data Anggota diambil (SELECT)
        Anggota::addGlobalScope('rayon', function (Builder $builder) {
            if (Auth::check() && !Auth::user()->hasRole('super_admin')) {
                /** @var \App\Models\User $user */
                $user = Auth::user();
                $builder->where('rayon_id', $user->rayon_id);
            }
        });

        // Event Listener: Berjalan hanya saat data Anggota akan dibuat (INSERT)
        Anggota::creating(function (Anggota $anggota) {
            if (Auth::check()) {
                /** @var \App\Models\User $user */
                $user = Auth::user();
                if (!$user->hasRole('super_admin') && is_null($anggota->rayon_id)) {
                    $anggota->rayon_id = $user->rayon_id;
                }
            }
        });


        // =================================================================
        // BAGIAN 3: LOGIKA UNTUK MODEL ARTIKEL
        // =================================================================

        // Aturan Global Scope: Berjalan setiap kali data Artikel diambil (SELECT)
        Article::addGlobalScope('rayon', function (Builder $builder) {
            if (Auth::check() && !Auth::user()->hasRole('super_admin')) {
                /** @var \App\Models\User $user */
                $user = Auth::user();
                // Filter artikel berdasarkan rayon user yang login
                $builder->where('rayon_id', $user->rayon_id);
            }
        });

        // Event Listener: Berjalan hanya saat data Artikel akan dibuat (INSERT)
        Article::creating(function (Article $article) {
            if (Auth::check()) {
                /** @var \App\Models\User $user */
                $user = Auth::user();
                if (!$user->hasRole('super_admin') && is_null($article->rayon_id)) {
                    $article->rayon_id = $user->rayon_id;
                }
            }
        });
    }
}
