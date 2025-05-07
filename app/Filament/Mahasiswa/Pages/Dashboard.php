<?php

namespace App\Filament\Mahasiswa\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static string $view = 'filament.mahasiswa.pages.dashboard';

    public function getTitle(): string
    {
        return 'Dashboard Mahasiswa';
    }
}
