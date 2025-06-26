<?php

namespace App\Providers;

use App\Services\GlobalSettingService;
use Illuminate\Support\ServiceProvider;

class GlobalSettingServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton('global-setting', function ($app) {
            return new GlobalSettingService();
        });
    }

    public function boot(): void
    {
        //
    }
}
