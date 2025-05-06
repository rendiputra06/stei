<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StatusMahasiswaResource\Pages;
use App\Models\StatusMahasiswa;
use App\Models\TahunAkademik;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StatusMahasiswaResource extends Resource
{
    protected static ?string $model = StatusMahasiswa::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $navigationGroup = 'Akademik';

    protected static ?int $navigationSort = 5;

    protected static ?string $recordTitleAttribute = 'id';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Mahasiswa dan Tahun Akademik')
                    ->schema([
                        Forms\Components\Select::make('mahasiswa_id')
                            ->relationship('mahasiswa', 'nama')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function (?string $state, ?string $old, Get $get, Set $set) {
                                $tahunAkademikId = $get('tahun_akademik_id');
                                if (empty($state) || empty($tahunAkademikId)) return;

                                $mahasiswa = \App\Models\Mahasiswa::find($state);
                                $tahunAkademik = \App\Models\TahunAkademik::find($tahunAkademikId);

                                if ($mahasiswa && $tahunAkademik) {
                                    $semester = StatusMahasiswa::hitungSemester($mahasiswa, $tahunAkademik);
                                    $set('semester', $semester);

                                    // Hitung perkiraan total SKS jika semester > 1 dan belum diisi
                                    if ($semester > 1 && empty($get('sks_total'))) {
                                        $set('sks_total', ($semester - 1) * 20);
                                    }
                                }
                            }),

                        Forms\Components\Select::make('tahun_akademik_id')
                            ->relationship('tahunAkademik', 'nama')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function (?string $state, ?string $old, Get $get, Set $set) {
                                $mahasiswaId = $get('mahasiswa_id');
                                if (empty($state) || empty($mahasiswaId)) return;

                                $mahasiswa = \App\Models\Mahasiswa::find($mahasiswaId);
                                $tahunAkademik = \App\Models\TahunAkademik::find($state);

                                if ($mahasiswa && $tahunAkademik) {
                                    $semester = StatusMahasiswa::hitungSemester($mahasiswa, $tahunAkademik);
                                    $set('semester', $semester);

                                    // Hitung perkiraan total SKS jika semester > 1 dan belum diisi
                                    if ($semester > 1 && empty($get('sks_total'))) {
                                        $set('sks_total', ($semester - 1) * 20);
                                    }
                                }
                            }),

                        Forms\Components\Select::make('status')
                            ->options(StatusMahasiswa::getStatusList())
                            ->default('tidak_aktif')
                            ->required(),

                        Forms\Components\TextInput::make('semester')
                            ->required()
                            ->disabled()
                            ->helperText('Semester dihitung otomatis berdasarkan tahun masuk mahasiswa dan tahun akademik.')
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(14),
                    ]),

                Forms\Components\Section::make('Data Akademik')
                    ->schema([
                        Forms\Components\Grid::make()
                            ->schema([
                                Forms\Components\TextInput::make('ip_semester')
                                    ->label('IP Semester')
                                    ->numeric()
                                    ->minValue(0)
                                    ->maxValue(4.0)
                                    ->step(0.01),

                                Forms\Components\TextInput::make('ipk')
                                    ->label('IPK')
                                    ->numeric()
                                    ->minValue(0)
                                    ->maxValue(4.0)
                                    ->step(0.01),
                            ]),

                        Forms\Components\Grid::make()
                            ->schema([
                                Forms\Components\TextInput::make('sks_semester')
                                    ->label('SKS Semester')
                                    ->numeric()
                                    ->default(0)
                                    ->minValue(0)
                                    ->maxValue(24),

                                Forms\Components\TextInput::make('sks_total')
                                    ->label('Total SKS')
                                    ->numeric()
                                    ->minValue(0),
                            ]),

                        Forms\Components\Textarea::make('keterangan')
                            ->default('Status mahasiswa belum dikonfirmasi')
                            ->rows(3),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('mahasiswa.nim')
                    ->label('NIM')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('mahasiswa.nama')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('tahunAkademik.nama')
                    ->label('Tahun Akademik')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('semester')
                    ->label('Semester')
                    ->sortable(),

                Tables\Columns\SelectColumn::make('status')
                    ->options(StatusMahasiswa::getStatusList())
                    ->sortable(),

                Tables\Columns\TextColumn::make('ip_semester')
                    ->label('IP Semester')
                    ->numeric(2)
                    ->sortable(),

                Tables\Columns\TextColumn::make('ipk')
                    ->label('IPK')
                    ->numeric(2)
                    ->sortable(),

                Tables\Columns\TextColumn::make('sks_semester')
                    ->label('SKS Semester')
                    ->sortable(),

                Tables\Columns\TextColumn::make('sks_total')
                    ->label('Total SKS')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('tahun_akademik_id')
                    ->label('Tahun Akademik')
                    ->relationship('tahunAkademik', 'nama')
                    ->default(function () {
                        $tahunAkademikAktif = TahunAkademik::getAktif();
                        return $tahunAkademikAktif ? $tahunAkademikAktif->id : null;
                    })
                    ->preload(),

                Tables\Filters\SelectFilter::make('status')
                    ->options(StatusMahasiswa::getStatusList()),

                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
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
            'index' => Pages\ListStatusMahasiswas::route('/'),
            'create' => Pages\CreateStatusMahasiswa::route('/create'),
            'view' => Pages\ViewStatusMahasiswa::route('/{record}'),
            'edit' => Pages\EditStatusMahasiswa::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
