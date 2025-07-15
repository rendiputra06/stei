<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MahasiswaResource\Pages;
use App\Models\Mahasiswa;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class MahasiswaResource extends Resource
{
    protected static ?string $model = Mahasiswa::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'Civitas Akademik';

    protected static ?int $navigationSort = 11;

    protected static ?string $navigationLabel = 'Mahasiswa';

    protected static ?string $title = 'Mahasiswa';

    protected static ?string $pluralModelLabel = 'Mahasiswa';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Mahasiswa')
                    ->schema([
                        Forms\Components\TextInput::make('nim')
                            ->required()
                            ->maxLength(20)
                            ->unique(ignoreRecord: true)
                            ->label('NIM'),
                        Forms\Components\TextInput::make('nama')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->required()
                            ->email()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->helperText('Email ini akan digunakan untuk login ke sistem'),
                        Forms\Components\TextInput::make('no_telepon')
                            ->maxLength(15),
                        Forms\Components\Select::make('jenis_kelamin')
                            ->required()
                            ->options([
                                'L' => 'Laki-laki',
                                'P' => 'Perempuan',
                            ]),
                        Forms\Components\DatePicker::make('tanggal_lahir')
                            ->maxDate(now()->subYears(15)),
                        Forms\Components\Textarea::make('alamat')
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Forms\Components\Select::make('program_studi_id')
                            ->relationship('programStudi', 'nama')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\TextInput::make('tahun_masuk')
                            ->required()
                            ->numeric()
                            ->minValue(2000)
                            ->maxValue(now()->year)
                            ->default(now()->year),
                        Forms\Components\Select::make('status')
                            ->required()
                            ->options([
                                'aktif' => 'Aktif',
                                'cuti' => 'Cuti',
                                'lulus' => 'Lulus',
                                'drop out' => 'Drop Out',
                            ])
                            ->default('aktif'),
                        Forms\Components\Toggle::make('is_active')
                            ->required()
                            ->default(true)
                            ->label('Status Aktif'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nim')
                    ->searchable()
                    ->sortable()
                    ->label('NIM'),
                Tables\Columns\TextColumn::make('nama')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('programStudi.nama')
                    ->sortable()
                    ->searchable()
                    ->label('Program Studi'),
                Tables\Columns\TextColumn::make('tahun_masuk')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'aktif' => 'success',
                        'cuti' => 'warning',
                        'lulus' => 'info',
                        'drop out' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('jenis_kelamin')
                    ->formatStateUsing(fn(string $state): string => $state === 'L' ? 'Laki-laki' : 'Perempuan'),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->label('Status Aktif'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('program_studi')
                    ->relationship('programStudi', 'nama'),
                Tables\Filters\SelectFilter::make('tahun_masuk')
                    ->options(function () {
                        $years = [];
                        $currentYear = now()->year;
                        for ($i = 0; $i < 6; $i++) {
                            $year = $currentYear - $i;
                            $years[$year] = $year;
                        }
                        return $years;
                    }),
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'aktif' => 'Aktif',
                        'cuti' => 'Cuti',
                        'lulus' => 'Lulus',
                        'drop out' => 'Drop Out',
                    ]),
                Tables\Filters\SelectFilter::make('jenis_kelamin')
                    ->options([
                        'L' => 'Laki-laki',
                        'P' => 'Perempuan',
                    ]),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status Aktif'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('ubahStatus')
                        ->label('Ubah Status')
                        ->icon('heroicon-o-arrow-path')
                        ->form([
                            Forms\Components\Select::make('status')
                                ->label('Status Baru')
                                ->options([
                                    'aktif' => 'Aktif',
                                    'cuti' => 'Cuti',
                                    'lulus' => 'Lulus',
                                    'drop out' => 'Drop Out',
                                ])
                                ->required(),
                        ])
                        ->action(function (array $data, \Illuminate\Database\Eloquent\Collection $records): void {
                            $records->each(function (Mahasiswa $record) use ($data): void {
                                $record->update(['status' => $data['status']]);
                            });
                        }),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMahasiswas::route('/'),
            'create' => Pages\CreateMahasiswa::route('/create'),
            'edit' => Pages\EditMahasiswa::route('/{record}/edit'),
        ];
    }
}
