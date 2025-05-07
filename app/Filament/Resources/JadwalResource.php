<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JadwalResource\Pages;
use App\Models\Jadwal;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class JadwalResource extends Resource
{
    protected static ?string $model = Jadwal::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    protected static ?string $navigationGroup = 'Akademik';

    protected static ?int $navigationSort = 5;

    protected static ?string $recordTitleAttribute = 'id';

    public static function getNavigationLabel(): string
    {
        return 'Jadwal Kuliah';
    }

    public static function getPluralLabel(): string
    {
        return 'Jadwal Kuliah';
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Select::make('tahun_akademik_id')
                            ->relationship('tahunAkademik', 'nama')
                            ->required()
                            ->searchable()
                            ->preload(),

                        Select::make('mata_kuliah_id')
                            ->relationship('mataKuliah', 'nama')
                            ->required()
                            ->searchable()
                            ->preload(),

                        Select::make('dosen_id')
                            ->relationship('dosen', 'nama')
                            ->required()
                            ->searchable()
                            ->preload(),

                        Select::make('ruangan_id')
                            ->relationship('ruangan', 'nama')
                            ->required()
                            ->searchable()
                            ->preload(),

                        Select::make('hari')
                            ->options([
                                'Senin' => 'Senin',
                                'Selasa' => 'Selasa',
                                'Rabu' => 'Rabu',
                                'Kamis' => 'Kamis',
                                'Jumat' => 'Jumat',
                                'Sabtu' => 'Sabtu',
                            ])
                            ->required(),

                        Forms\Components\TimePicker::make('jam_mulai')
                            ->seconds(false)
                            ->required(),

                        Forms\Components\TimePicker::make('jam_selesai')
                            ->seconds(false)
                            ->required()
                            ->after('jam_mulai')
                            ->rule(function (Forms\Get $get) {
                                return function (string $attribute, $value, \Closure $fail) use ($get) {
                                    $jamMulai = strtotime($get('jam_mulai'));
                                    $jamSelesai = strtotime($value);
                                    if ($jamSelesai <= $jamMulai) {
                                        $fail('Jam selesai harus lebih besar dari jam mulai.');
                                    }
                                };
                            }),

                        TextInput::make('kelas')
                            ->required()
                            ->maxLength(10),

                        Toggle::make('is_active')
                            ->label('Status Aktif')
                            ->default(true),
                    ])->columns(2),
            ])
            ->statePath('data');
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tahunAkademik.nama')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('mataKuliah.nama')
                    ->searchable()
                    ->sortable()
                    ->description(fn(Jadwal $record): string => $record->mataKuliah->kode ?? ''),

                TextColumn::make('dosen.nama')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('ruangan.nama')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('hari')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('jam_mulai')
                    ->label('Jam')
                    ->formatStateUsing(
                        fn(Jadwal $record): string =>
                        date('H:i', strtotime($record->jam_mulai)) . ' - ' .
                            date('H:i', strtotime($record->jam_selesai))
                    ),

                TextColumn::make('kelas')
                    ->searchable()
                    ->sortable(),

                ToggleColumn::make('is_active')
                    ->label('Aktif'),
            ])
            ->filters([
                SelectFilter::make('tahun_akademik_id')
                    ->relationship('tahunAkademik', 'nama')
                    ->label('Tahun Akademik'),

                SelectFilter::make('hari')
                    ->options([
                        'Senin' => 'Senin',
                        'Selasa' => 'Selasa',
                        'Rabu' => 'Rabu',
                        'Kamis' => 'Kamis',
                        'Jumat' => 'Jumat',
                        'Sabtu' => 'Sabtu',
                    ]),

                SelectFilter::make('is_active')
                    ->options([
                        '1' => 'Aktif',
                        '0' => 'Tidak Aktif',
                    ])
                    ->label('Status'),

                SelectFilter::make('ruangan_id')
                    ->relationship('ruangan', 'nama')
                    ->label('Ruangan'),

                SelectFilter::make('dosen_id')
                    ->relationship('dosen', 'nama')
                    ->searchable()
                    ->label('Dosen'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListJadwals::route('/'),
            'create' => Pages\CreateJadwal::route('/create'),
            'edit' => Pages\EditJadwal::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['tahunAkademik', 'mataKuliah', 'dosen', 'ruangan']);
    }
}
