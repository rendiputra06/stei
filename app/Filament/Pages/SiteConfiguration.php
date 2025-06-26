<?php

namespace App\Filament\Pages;

use App\Models\GlobalSetting;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Cache;

class SiteConfiguration extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationGroup = 'Admin Management';
    protected static ?string $navigationLabel = 'Konfigurasi Situs';
    protected static ?int $navigationSort = 85;
    protected static string $view = 'filament.pages.site-configuration';

    public ?array $data = [];

    public function mount(): void
    {
        $settings = GlobalSetting::all()->groupBy('group');
        $formData = [];

        foreach ($settings as $group => $groupSettings) {
            foreach ($groupSettings as $setting) {
                $formData[$setting->key] = $setting->value;
            }
        }

        $this->form->fill($formData);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Pengaturan Situs')->tabs([
                    Tabs\Tab::make('Pengaturan Umum')
                        ->schema([
                            TextInput::make('site_name')
                                ->label('Nama Situs')
                                ->required()
                                ->maxLength(255),
                            Textarea::make('site_description')
                                ->label('Deskripsi Situs')
                                ->maxLength(65535),
                            FileUpload::make('site_logo')
                                ->label('Logo Situs')
                                ->image()
                                ->directory('settings'),
                            FileUpload::make('site_favicon')
                                ->label('Favicon')
                                ->image()
                                ->directory('settings'),
                        ])
                        ->columns(2),

                    Tabs\Tab::make('Kontak')
                        ->schema([
                            TextInput::make('contact_email')
                                ->label('Email')
                                ->email()
                                ->required()
                                ->maxLength(255),
                            TextInput::make('contact_phone')
                                ->label('Telepon')
                                ->tel()
                                ->required()
                                ->maxLength(255),
                            Textarea::make('contact_address')
                                ->label('Alamat')
                                ->required()
                                ->maxLength(65535),
                        ])
                        ->columns(2),

                    Tabs\Tab::make('Media Sosial')
                        ->schema([
                            Repeater::make('social_media')
                                ->schema([
                                    TextInput::make('key')
                                        ->label('Platform')
                                        ->required(),
                                    TextInput::make('value')
                                        ->label('URL')
                                        ->url()
                                        ->required(),
                                ])
                                ->columns(2)
                                ->defaultItems(0)
                                ->reorderable(false)
                                ->addActionLabel('Tambah Media Sosial'),
                        ]),

                    Tabs\Tab::make('SEO')
                        ->schema([
                            TextInput::make('meta_title')
                                ->label('Meta Title')
                                ->required()
                                ->maxLength(255),
                            Textarea::make('meta_description')
                                ->label('Meta Description')
                                ->required()
                                ->maxLength(65535),
                            TextInput::make('meta_keywords')
                                ->label('Meta Keywords')
                                ->required()
                                ->maxLength(255),
                        ])
                        ->columns(2),

                    Tabs\Tab::make('Tampilan')
                        ->schema([
                            TextInput::make('theme_color_primary')
                                ->label('Warna Primer')
                                ->type('color'),
                            TextInput::make('theme_color_secondary')
                                ->label('Warna Sekunder')
                                ->type('color'),
                            TextInput::make('theme_color_dark')
                                ->label('Warna Gelap')
                                ->type('color'),
                            TextInput::make('theme_color_light')
                                ->label('Warna Terang')
                                ->type('color'),
                        ])
                        ->columns(2),
                ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        foreach ($data as $key => $value) {
            $setting = GlobalSetting::firstOrNew(['key' => $key]);

            // Tentukan tipe berdasarkan key
            $type = match ($key) {
                'site_description', 'contact_address', 'meta_description' => 'text',
                'social_media' => 'array',
                'site_logo', 'site_favicon' => 'image',
                default => 'string'
            };

            // Tentukan group berdasarkan key
            $group = match ($key) {
                'site_name', 'site_description', 'site_logo', 'site_favicon' => 'general',
                'contact_email', 'contact_phone', 'contact_address' => 'contact',
                'social_media' => 'social',
                'meta_title', 'meta_description', 'meta_keywords' => 'seo',
                'theme_color_primary', 'theme_color_secondary', 'theme_color_dark', 'theme_color_light' => 'appearance',
                default => 'general'
            };

            $setting->fill([
                'value' => $value,
                'type' => $type,
                'group' => $group,
            ])->save();
        }

        // Clear cache
        app(\App\Services\GlobalSettingService::class)->clearCache();

        Notification::make()
            ->title('Pengaturan berhasil disimpan')
            ->success()
            ->send();
    }
}
