<?php

namespace App\Filament\Dosen\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static string $view = 'filament.dosen.pages.dashboard';

    public function getTitle(): string
    {
        return 'Dashboard Dosen';
    }
}
