<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use App\Models\MataKuliah;
use App\Models\ProgramStudi;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Mahasiswa', Mahasiswa::where('is_active', 'aktif')->count())
                ->description('Mahasiswa Aktif')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('success'),

            Stat::make('Total Dosen', Dosen::where('is_active', 'aktif')->count())
                ->description('Dosen Aktif')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('primary'),

            Stat::make('Total Mata Kuliah', MataKuliah::count())
                ->description('Mata Kuliah Aktif')
                ->descriptionIcon('heroicon-m-book-open')
                ->color('warning'),

            Stat::make('Program Studi', ProgramStudi::count())
                ->description('Program Studi Aktif')
                ->descriptionIcon('heroicon-m-building-office')
                ->color('info'),
        ];
    }
}
