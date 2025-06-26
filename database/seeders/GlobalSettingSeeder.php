<?php

namespace Database\Seeders;

use App\Services\GlobalSettingService;
use Illuminate\Database\Seeder;

class GlobalSettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            [
                'key' => 'site_name',
                'value' => 'Sistem Informasi Akademik',
                'type' => 'string',
                'group' => 'general',
                'description' => 'Nama website',
            ],
            [
                'key' => 'site_description',
                'value' => 'Sistem Informasi Akademik untuk mengelola data akademik',
                'type' => 'text',
                'group' => 'general',
                'description' => 'Deskripsi website',
            ],
            [
                'key' => 'site_logo',
                'value' => 'default-logo.png',
                'type' => 'image',
                'group' => 'appearance',
                'description' => 'Logo website',
            ],
            [
                'key' => 'site_favicon',
                'value' => 'default-favicon.ico',
                'type' => 'image',
                'group' => 'appearance',
                'description' => 'Favicon website',
            ],
            [
                'key' => 'contact_email',
                'value' => 'admin@example.com',
                'type' => 'string',
                'group' => 'contact',
                'description' => 'Email kontak',
            ],
            [
                'key' => 'contact_phone',
                'value' => '+62 123 4567 890',
                'type' => 'string',
                'group' => 'contact',
                'description' => 'Nomor telepon kontak',
            ],
            [
                'key' => 'contact_address',
                'value' => 'Jl. Contoh No. 123, Kota, Provinsi',
                'type' => 'text',
                'group' => 'contact',
                'description' => 'Alamat kontak',
            ],
            [
                'key' => 'social_media',
                'value' => [
                    ['key' => 'facebook', 'value' => 'https://facebook.com/example'],
                    ['key' => 'twitter', 'value' => 'https://twitter.com/example'],
                    ['key' => 'instagram', 'value' => 'https://instagram.com/example'],
                ],
                'type' => 'array',
                'group' => 'social',
                'description' => 'Link media sosial',
            ],
            [
                'key' => 'meta_title',
                'value' => 'Sistem Informasi Akademik',
                'type' => 'string',
                'group' => 'seo',
                'description' => 'Meta title untuk SEO',
            ],
            [
                'key' => 'meta_description',
                'value' => 'Sistem Informasi Akademik untuk mengelola data akademik',
                'type' => 'text',
                'group' => 'seo',
                'description' => 'Meta description untuk SEO',
            ],
            [
                'key' => 'meta_keywords',
                'value' => 'sistem informasi,akademik,pendidikan',
                'type' => 'string',
                'group' => 'seo',
                'description' => 'Meta keywords untuk SEO',
            ],
        ];

        $service = app(GlobalSettingService::class);

        foreach ($settings as $setting) {
            $service->set(
                $setting['key'],
                $setting['value'],
                $setting['type'],
                $setting['group'],
                $setting['description']
            );
        }
    }
}
