<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default Filament Authentication Route Names
    |--------------------------------------------------------------------------
    |
    | Pengaturan ini digunakan untuk mengkonfigurasi rute login dan logout kustom
    | yang digunakan oleh Filament.
    |
    */
    'auth' => [
        'guard' => env('FILAMENT_AUTH_GUARD', 'web'),
        'pages' => [
            'login' => null, // Menonaktifkan halaman login default Filament
        ],
    ],

    'home_url' => '/',
    'path' => env('FILAMENT_PATH', 'admin'),
    'domain' => env('FILAMENT_DOMAIN'),

    'pages' => [
        'namespace' => 'App\\Filament\\Pages',
        'path' => app_path('Filament/Pages'),
        'register' => [],
    ],
    'resources' => [
        'namespace' => 'App\\Filament\\Resources',
        'path' => app_path('Filament/Resources'),
        'register' => [],
    ],
    'widgets' => [
        'namespace' => 'App\\Filament\\Widgets',
        'path' => app_path('Filament/Widgets'),
        'register' => [],
    ],
    'middleware' => [
        'auth' => [
            \Filament\Http\Middleware\Authenticate::class,
        ],
        'base' => [
            \Illuminate\Cookie\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ],
];
