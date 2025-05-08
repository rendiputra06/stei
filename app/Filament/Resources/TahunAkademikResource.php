<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TahunAkademikResource\Pages;
use App\Models\TahunAkademik;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TahunAkademikResource extends Resource
{
    protected static ?string $model = TahunAkademik::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    protected static ?string $navigationGroup = 'Manajemen Akademik';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'nama';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Tahun Akademik')
                    ->schema([
                        Forms\Components\TextInput::make('kode')
                            ->required()
                            ->maxLength(20)
                            ->unique(ignoreRecord: true)
                            ->helperText('Format: Tahun dan kode semester (contoh: 20241 untuk Ganjil 2024/2025)'),

                        Forms\Components\Grid::make()
                            ->schema([
                                Forms\Components\TextInput::make('tahun')
                                    ->required()
                                    ->numeric()
                                    ->minValue(2000)
                                    ->maxValue(2100)
                                    ->helperText('Tahun akademik, contoh: 2024'),

                                Forms\Components\Select::make('semester')
                                    ->required()
                                    ->options([
                                        'Ganjil' => 'Ganjil',
                                        'Genap' => 'Genap',
                                        'Pendek' => 'Pendek',
                                    ]),
                            ]),

                        Forms\Components\TextInput::make('nama')
                            ->required()
                            ->maxLength(255)
                            ->helperText('Nama lengkap, contoh: Semester Ganjil 2024/2025'),

                        Forms\Components\Toggle::make('aktif')
                            ->helperText('Hanya boleh ada satu tahun akademik yang aktif. Mengaktifkan tahun akademik ini akan menonaktifkan tahun akademik lainnya.')
                            ->reactive(),
                    ]),

                Forms\Components\Section::make('Periode Tahun Akademik')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\DatePicker::make('tanggal_mulai')
                                    ->required()
                                    ->displayFormat('d/m/Y')
                                    ->closeOnDateSelection(),

                                Forms\Components\DatePicker::make('tanggal_selesai')
                                    ->required()
                                    ->displayFormat('d/m/Y')
                                    ->closeOnDateSelection()
                                    ->after('tanggal_mulai'),
                            ]),
                    ]),

                Forms\Components\Section::make('Periode KRS')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\DateTimePicker::make('krs_mulai')
                                    ->displayFormat('d/m/Y H:i')
                                    ->seconds(false),

                                Forms\Components\DateTimePicker::make('krs_selesai')
                                    ->displayFormat('d/m/Y H:i')
                                    ->seconds(false)
                                    ->after('krs_mulai'),
                            ]),
                    ]),

                Forms\Components\Section::make('Periode Pengisian Nilai')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\DateTimePicker::make('nilai_mulai')
                                    ->displayFormat('d/m/Y H:i')
                                    ->seconds(false),

                                Forms\Components\DateTimePicker::make('nilai_selesai')
                                    ->displayFormat('d/m/Y H:i')
                                    ->seconds(false)
                                    ->after('nilai_mulai'),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kode')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('nama')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\IconColumn::make('aktif')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('tanggal_mulai')
                    ->date('d/m/Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('tanggal_selesai')
                    ->date('d/m/Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('krs_mulai')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('krs_selesai')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('nilai_mulai')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('nilai_selesai')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('semester')
                    ->options([
                        'Ganjil' => 'Ganjil',
                        'Genap' => 'Genap',
                        'Pendek' => 'Pendek',
                    ]),

                Tables\Filters\Filter::make('aktif')
                    ->query(fn(Builder $query): Builder => $query->where('aktif', true))
                    ->toggle(),

                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
                Tables\Actions\Action::make('activate')
                    ->label('Aktifkan')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn(TahunAkademik $record): bool => !$record->aktif)
                    ->action(function (TahunAkademik $record): void {
                        // Nonaktifkan semua tahun akademik
                        TahunAkademik::query()->update(['aktif' => false]);

                        // Aktifkan tahun akademik yang dipilih
                        $record->aktif = true;
                        $record->save();
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
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
            'index' => Pages\ListTahunAkademiks::route('/'),
            'create' => Pages\CreateTahunAkademik::route('/create'),
            'view' => Pages\ViewTahunAkademik::route('/{record}'),
            'edit' => Pages\EditTahunAkademik::route('/{record}/edit'),
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
