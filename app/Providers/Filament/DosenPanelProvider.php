<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use App\Http\Middleware\EnsureUserHasRole;
use Filament\Navigation\MenuItem;
use App\Filament\Dosen\Widgets\PendingKRSWidget;

class DosenPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('dosen')
            ->path('dosen')
            ->authGuard('web')
            ->login(false)
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Dosen/Resources'), for: 'App\\Filament\\Dosen\\Resources')
            ->discoverPages(in: app_path('Filament/Dosen/Pages'), for: 'App\\Filament\\Dosen\\Pages')
            ->pages([
                // Pages\Dashboard::class,
                \App\Filament\Dosen\Pages\AbsensiQRCode::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Dosen/Widgets'), for: 'App\\Filament\\Dosen\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                \App\Filament\Dosen\Widgets\JadwalMengajarWidget::class,
                \App\Filament\Dosen\Widgets\PendingKrsWidget::class,
                \App\Filament\Dosen\Widgets\MahasiswaBimbinganWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
                EnsureUserHasRole::class . ':dosen',
            ])
            ->userMenuItems([
                // Tambahkan tombol "Return to Admin" jika dalam mode Login As
                MenuItem::make()
                    ->label('Kembali ke Admin')
                    ->url(fn() => route('return-to-admin'))
                    ->icon('heroicon-o-arrow-left-circle')
                    ->visible(fn() => session()->has('admin_id') && session()->has('login_as')),
            ]);
    }
}
