<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\KRS;

class AkademikOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('KRS Disetujui', KRS::where('status', 'disetujui')->count())
                ->description('KRS yang Sudah Disetujui')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

            Stat::make('KRS Pending', KRS::where('status', 'pending')->count())
                ->description('KRS yang Menunggu Persetujuan')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),
        ];
    }
}
