<?php

namespace App\Services;

use App\Models\GlobalSetting;
use Illuminate\Support\Facades\Cache;

class GlobalSettingService
{
    public function get(string $key, $default = null)
    {
        return Cache::rememberForever('global_setting_' . $key, function () use ($key, $default) {
            $setting = GlobalSetting::where('key', $key)->first();

            if (!$setting) {
                return $default;
            }

            return $setting->value;
        });
    }

    public function set(string $key, $value, string $type = 'string', string $group = 'general', ?string $description = null): GlobalSetting
    {
        $setting = GlobalSetting::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'type' => $type,
                'group' => $group,
                'description' => $description,
            ]
        );

        Cache::forget('global_setting_' . $key);

        return $setting;
    }

    public function forget(string $key): void
    {
        Cache::forget('global_setting_' . $key);
    }

    public function all(): array
    {
        return Cache::rememberForever('global_settings_all', function () {
            return GlobalSetting::all()->pluck('value', 'key')->toArray();
        });
    }

    public function clearCache(): void
    {
        Cache::forget('global_settings_all');
        GlobalSetting::all()->each(function ($setting) {
            Cache::forget('global_setting_' . $setting->key);
        });
    }
}
