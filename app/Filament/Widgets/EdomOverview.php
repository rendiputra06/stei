<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\EdomPengisian;

class EdomOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('EDOM Terisi', EdomPengisian::where('status', 'selesai')->count())
                ->description('EDOM yang Sudah Diisi')
                ->descriptionIcon('heroicon-m-document-check')
                ->color('success'),

            Stat::make('EDOM Pending', EdomPengisian::where('status', 'pending')->count())
                ->description('EDOM yang Belum Diisi')
                ->descriptionIcon('heroicon-m-document')
                ->color('warning'),
        ];
    }
}
