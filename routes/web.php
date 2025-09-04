<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// --- Import Semua Controller yang Digunakan ---
use App\Http\Controllers\AnggotaProfileController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DownloadPageController; // <-- Ditambahkan
use App\Http\Controllers\HomepageController;
use App\Http\Controllers\MapabaRegistrationController;
use App\Http\Controllers\OrganizationProfileController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Rute Publik (Dapat Diakses Semua Pengunjung)
|--------------------------------------------------------------------------
*/
Route::get('/', HomepageController::class)->name('homepage');

// Rute untuk Artikel
Route::get('/artikel', [ArticleController::class, 'index'])->name('articles.index');
Route::get('/artikel/{article:slug}', [ArticleController::class, 'show'])->name('articles.show');

// Rute untuk Halaman Profil
Route::get('/profil/rayon/{rayon:slug}', [OrganizationProfileController::class, 'showRayon'])->name('rayon.profil.show');
Route::get('/profil/komisariat', [OrganizationProfileController::class, 'showKomisariat'])->name('profil.organisasi'); // <-- Rute Baru Ditambahkan

// // Rute untuk Halaman Download
// Route::get('/downloads', [DownloadPageController::class, 'index'])->name('downloads.index'); // <-- Rute Baru Ditambahkan

// Rute untuk Pendaftaran Anggota Baru (MAPABA)
Route::get('/pendaftaran-mapaba', [MapabaRegistrationController::class, 'create'])->name('pendaftaran.mapaba.create');
Route::post('/pendaftaran-mapaba', [MapabaRegistrationController::class, 'store'])->name('pendaftaran.mapaba.store');


/*
|--------------------------------------------------------------------------
| Rute Terproteksi (Hanya untuk User yang Sudah Login)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    // Rute Dashboard
    Route::get('/dashboard', DashboardController::class)->middleware('verified')->name('dashboard');

    // Rute untuk mengelola profil user bawaan Laravel (email, password)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rute untuk mengelola profil keanggotaan
    Route::get('/profil-anggota', [AnggotaProfileController::class, 'edit'])->name('anggota.profile.edit');
    Route::patch('/profil-anggota', [AnggotaProfileController::class, 'update'])->name('anggota.profile.update');
    Route::get('/profil-anggota/kartu/download', [AnggotaProfileController::class, 'downloadCard'])->name('anggota.card.download');
});

// Rute Autentikasi Bawaan Laravel (Login, Register, dll.)
require __DIR__.'/auth.php';
