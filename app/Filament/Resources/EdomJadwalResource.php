<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EdomJadwalResource\Pages;
use App\Filament\Resources\EdomJadwalResource\RelationManagers;
use App\Models\EdomJadwal;
use App\Models\EdomTemplate;
use App\Models\TahunAkademik;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EdomJadwalResource extends Resource
{
    protected static ?string $model = EdomJadwal::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    protected static ?string $navigationGroup = 'Evaluasi Dosen';

    protected static ?int $navigationSort = 3;

    protected static ?string $pluralModelLabel = 'Jadwal Evaluasi';

    protected static ?string $modelLabel = 'Jadwal Evaluasi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Jadwal Evaluasi')
                    ->schema([
                        Forms\Components\Select::make('tahun_akademik_id')
                            ->label('Tahun Akademik')
                            ->options(
                                fn() => TahunAkademik::query()
                                    ->orderBy('tahun', 'desc')
                                    ->orderBy('semester', 'desc')
                                    ->get()
                                    ->mapWithKeys(fn($item) => [$item->id => $item->nama])
                            )
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\Select::make('template_id')
                            ->label('Template Evaluasi')
                            ->options(
                                fn() => EdomTemplate::where('is_aktif', true)
                                    ->pluck('nama_template', 'id')
                            )
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\TextInput::make('nama_periode')
                            ->label('Nama Periode')
                            ->placeholder('Evaluasi Tengah Semester/Akhir Semester')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Forms\Components\DatePicker::make('tanggal_mulai')
                            ->label('Tanggal Mulai')
                            ->required(),
                        Forms\Components\DatePicker::make('tanggal_selesai')
                            ->label('Tanggal Selesai')
                            ->required()
                            ->afterOrEqual('tanggal_mulai'),
                        Forms\Components\Toggle::make('is_aktif')
                            ->label('Status Aktif')
                            ->default(true)
                            ->required(),
                        Forms\Components\Textarea::make('deskripsi')
                            ->label('Deskripsi')
                            ->maxLength(65535)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tahunAkademik.nama')
                    ->label('Tahun Akademik')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('template.nama_template')
                    ->label('Template')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nama_periode')
                    ->label('Nama Periode')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tanggal_mulai')
                    ->label('Tanggal Mulai')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tanggal_selesai')
                    ->label('Tanggal Selesai')
                    ->date()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_aktif')
                    ->label('Status')
                    ->boolean(),
                Tables\Columns\TextColumn::make('pengisian_count')
                    ->label('Jumlah Pengisian')
                    ->counts('pengisian'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diperbarui Pada')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('tahun_akademik_id')
                    ->label('Tahun Akademik')
                    ->relationship('tahunAkademik', 'nama')
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('template_id')
                    ->label('Template')
                    ->relationship('template', 'nama_template')
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('is_aktif')
                    ->label('Status')
                    ->options([
                        '1' => 'Aktif',
                        '0' => 'Tidak Aktif',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListEdomJadwals::route('/'),
            'create' => Pages\CreateEdomJadwal::route('/create'),
            'edit' => Pages\EditEdomJadwal::route('/{record}/edit'),
        ];
    }
}
