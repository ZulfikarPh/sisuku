<?php

namespace App\Providers;

use App\Models\Anggota;
use App\Policies\AnggotaPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // Baris ini memberitahu Laravel:
        // "Untuk model Anggota, gunakan aturan dari AnggotaPolicy"
        Anggota::class => AnggotaPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
