<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EdomPengisianResource\Pages;
use App\Filament\Resources\EdomPengisianResource\RelationManagers;
use App\Models\EdomPengisian;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EdomPengisianResource extends Resource
{
    protected static ?string $model = EdomPengisian::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Evaluasi Dosen';

    protected static ?int $navigationSort = 4;

    protected static ?string $pluralModelLabel = 'Hasil Evaluasi';

    protected static ?string $modelLabel = 'Hasil Evaluasi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Detail Pengisian Evaluasi')
                    ->schema([
                        Forms\Components\TextInput::make('jadwal.nama_periode')
                            ->label('Periode Evaluasi')
                            ->disabled(),
                        Forms\Components\TextInput::make('mahasiswa.nama_lengkap')
                            ->label('Mahasiswa')
                            ->disabled(),
                        Forms\Components\TextInput::make('mataKuliah.nama_mata_kuliah')
                            ->label('Mata Kuliah')
                            ->disabled(),
                        Forms\Components\TextInput::make('dosen.nama_lengkap')
                            ->label('Dosen')
                            ->disabled(),
                        Forms\Components\DatePicker::make('tanggal_pengisian')
                            ->label('Tanggal Pengisian')
                            ->disabled(),
                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'draft' => 'Draft',
                                'submitted' => 'Sudah Disubmit',
                            ])
                            ->disabled(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('jadwal.tahunAkademik.nama_lengkap')
                    ->label('Tahun Akademik')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('jadwal.nama_periode')
                    ->label('Periode Evaluasi')
                    ->searchable(),
                Tables\Columns\TextColumn::make('mahasiswa.nama_lengkap')
                    ->label('Mahasiswa')
                    ->searchable(),
                Tables\Columns\TextColumn::make('mataKuliah.nama_mata_kuliah')
                    ->label('Mata Kuliah')
                    ->searchable(),
                Tables\Columns\TextColumn::make('dosen.nama_lengkap')
                    ->label('Dosen')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tanggal_pengisian')
                    ->label('Tanggal Pengisian')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'draft' => 'Draft',
                        'submitted' => 'Sudah Disubmit',
                        default => $state,
                    })
                    ->colors([
                        'warning' => 'draft',
                        'success' => 'submitted',
                    ]),
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
                Tables\Filters\SelectFilter::make('jadwal_id')
                    ->label('Periode Evaluasi')
                    ->relationship('jadwal', 'nama_periode')
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'draft' => 'Draft',
                        'submitted' => 'Sudah Disubmit',
                    ]),
                Tables\Filters\Filter::make('tanggal_pengisian')
                    ->form([
                        Forms\Components\DatePicker::make('tanggal_dari')
                            ->label('Dari Tanggal'),
                        Forms\Components\DatePicker::make('tanggal_sampai')
                            ->label('Sampai Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['tanggal_dari'],
                                fn(Builder $query, $date): Builder => $query->whereDate('tanggal_pengisian', '>=', $date),
                            )
                            ->when(
                                $data['tanggal_sampai'],
                                fn(Builder $query, $date): Builder => $query->whereDate('tanggal_pengisian', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            // Sementara kosong karena relation manager belum dibuat
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEdomPengisians::route('/'),
        ];
    }
}
