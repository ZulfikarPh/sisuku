<?php

namespace App\Filament\Widgets;

use App\Models\Anggota;
use App\Models\Member; // Pastikan Anda mengimpor model Member Anda
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class MemberStatsOverview extends BaseWidget
{
    protected static ?int $sort = 1; // Urutan widget di dashboard

    protected function getStats(): array
    {
        return [
            Stat::make('Total Anggota', Anggota::count())
                ->description('Jumlah keseluruhan anggota')
                ->descriptionIcon('heroicon-m-users')
                ->color('info'), // Warna badge
            // Anda bisa menambahkan statistik lain di sini, contoh:
            // Stat::make('Anggota Aktif', Member::where('status', 'aktif')->count())
            //     ->description('Anggota dengan status aktif')
            //     ->descriptionIcon('heroicon-m-check-circle')
            //     ->color('success'),
        ];
    }
}
