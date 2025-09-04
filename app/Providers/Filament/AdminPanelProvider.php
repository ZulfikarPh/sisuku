<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\MaxWidth;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

// Impor untuk kustomisasi navigasi dan footer
use Filament\Navigation\NavigationGroup;
use Filament\Navigation\NavigationItem; // Ditambahkan untuk item navigasi kustom
use Filament\View\PanelsRenderHook; // Ditambahkan untuk render hook

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->profile() // Mengaktifkan halaman profil user

            // --- 1. BRANDING & IDENTITAS VISUAL ---
            ->brandName('SISUKU PMII')
            ->brandLogo(asset('images/logo-pmii.png')) // Pastikan file ada di public/images/
            ->brandLogoHeight('2.5rem') // Atur tinggi logo agar tampil rapi
            ->favicon(asset('images/logo-pmii.png')) // Ikon untuk tab browser
            ->colors([
                'primary' => Color::Blue,   // Warna biru khas PMII
                'warning' => Color::Amber,
                'danger' => Color::Red,
                'success' => Color::Green,
                'info' => Color::Sky,
                // Anda bisa menambahkan warna lain jika diperlukan, misalnya 'gray' untuk nuansa abu-abu
                'gray' => Color::Slate,
            ])
            ->font('Inter') // Font modern yang mudah dibaca

            // --- 2. LAYOUT & PENGALAMAN PENGGUNA (UX) ---
            ->maxContentWidth(MaxWidth::Full) // Membuat layout konten menjadi lebar penuh
            ->sidebarCollapsibleOnDesktop()   // Membuat sidebar bisa diciutkan di desktop
            ->sidebarWidth('18rem') // Atur lebar sidebar jika diperlukan
            ->globalSearchKeyBindings(['command+k', 'ctrl+k']) // Aktifkan pencarian global dengan Ctrl+K
            ->globalSearch(true) // Memastikan fitur pencarian global aktif
            ->breadcrumbs(true) // Mengaktifkan breadcrumbs untuk navigasi yang lebih baik
            ->spa() // Mengaktifkan Single Page Application untuk navigasi yang lebih cepat

            // --- 3. PENGELOMPOKAN MENU NAVIGASI ---
            ->navigationGroups([
                NavigationGroup::make('Akademik & Keanggotaan')
                    ->icon('heroicon-o-academic-cap'), // Icon yang lebih relevan
                NavigationGroup::make('Publikasi & Konten')
                    ->icon('heroicon-o-document-text'),
                NavigationGroup::make('Manajemen Website')
                    ->icon('heroicon-o-globe-alt'),
                NavigationGroup::make('Administrasi Sistem')
                    ->icon('heroicon-o-cog-6-tooth')
                    ->collapsed(), // Dibuat terlipat secara default
                // Anda bisa menambahkan grup navigasi lain di sini
            ])
            // --- 4. ITEM NAVIGASI KUSTOM (opsional) ---
            // Berguna jika ada link eksternal atau halaman non-Filament yang ingin ditampilkan di sidebar
            ->navigationItems([
                NavigationItem::make('Kunjungi Website Utama')
                    ->url('https://pmii.or.id', shouldOpenInNewTab: true) // Ganti dengan URL website utama Anda
                    ->icon('heroicon-o-link')
                    ->group('Manajemen Website') // Masukkan ke grup yang relevan
                    ->sort(3), // Urutan dalam grup (setelah Resource dan Page)
            ])

            // --- 5. DISCOVERY SUMBER DAYA & HALAMAN ---
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
                // Anda bisa menambahkan halaman kustom lain di sini
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                // Widgets\FilamentInfoWidget::class, // Widget informasi Filament (opsional, untuk debugging)
                // Anda bisa menambahkan widget kustom lain di sini untuk dashboard
            ])

            // --- 6. MIDDLEWARE ---
            // Pastikan urutan middleware sudah benar untuk keamanan dan fungsionalitas
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                \Laravel\Sanctum\Http\Middleware\AuthenticateSession::class, // Penting untuk otentikasi API/SPA
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])

            // --- 7. KUSTOMISASI LAINNYA ---
            // Footer kustom
            ->renderHook(
                PanelsRenderHook::BODY_END,
                fn () => view('filament.custom.footer'), // Buat file blade ini di resources/views/filament/custom/footer.blade.php
            );
    }
}
