<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class GlobalSetting extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'global-setting';
    }
}
