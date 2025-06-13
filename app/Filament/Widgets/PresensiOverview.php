<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Presensi;

class PresensiOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $bulanIni = now()->startOfMonth();

        return [
            Stat::make('Presensi Mahasiswa', Presensi::whereMonth('tanggal', $bulanIni->month)->count())
                ->description('Presensi Bulan Ini')
                ->descriptionIcon('heroicon-m-calendar')
                ->color('success'),

            Stat::make('Presensi Dosen', Presensi::whereMonth('tanggal', $bulanIni->month)->count())
                ->description('Presensi Bulan Ini')
                ->descriptionIcon('heroicon-m-calendar')
                ->color('primary'),
        ];
    }
}
