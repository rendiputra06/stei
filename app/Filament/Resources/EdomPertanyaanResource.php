<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EdomPertanyaanResource\Pages;
use App\Filament\Resources\EdomPertanyaanResource\RelationManagers;
use App\Models\EdomPertanyaan;
use App\Models\EdomTemplate;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EdomPertanyaanResource extends Resource
{
    protected static ?string $model = EdomPertanyaan::class;

    protected static ?string $navigationIcon = 'heroicon-o-question-mark-circle';

    protected static ?string $navigationGroup = 'Evaluasi Dosen';

    protected static ?int $navigationSort = 2;

    protected static ?string $pluralModelLabel = 'Pertanyaan Evaluasi';

    protected static ?string $modelLabel = 'Pertanyaan Evaluasi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Pertanyaan')
                    ->schema([
                        Forms\Components\Select::make('template_id')
                            ->label('Template Evaluasi')
                            ->relationship('template', 'nama_template')
                            ->options(fn() => EdomTemplate::pluck('nama_template', 'id')->toArray())
                            ->searchable()
                            ->preload()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('nama_template')
                                    ->label('Nama Template')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\Textarea::make('deskripsi')
                                    ->label('Deskripsi')
                                    ->maxLength(65535),
                                Forms\Components\Toggle::make('is_aktif')
                                    ->label('Status Aktif')
                                    ->default(true),
                            ])
                            ->required(),
                        Forms\Components\TextInput::make('urutan')
                            ->label('Urutan')
                            ->numeric()
                            ->default(1)
                            ->required(),
                        Forms\Components\Textarea::make('pertanyaan')
                            ->label('Pertanyaan')
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\Select::make('jenis')
                            ->label('Jenis Pertanyaan')
                            ->options([
                                'likert_scale' => 'Skala Likert (1-5)',
                                'text' => 'Teks',
                            ])
                            ->default('likert_scale')
                            ->required(),
                        Forms\Components\Toggle::make('is_required')
                            ->label('Wajib Diisi')
                            ->default(true),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('template.nama_template')
                    ->label('Template')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('urutan')
                    ->label('Urutan')
                    ->sortable(),
                Tables\Columns\TextColumn::make('pertanyaan')
                    ->label('Pertanyaan')
                    ->limit(50)
                    ->searchable(),
                Tables\Columns\TextColumn::make('jenis')
                    ->label('Jenis')
                    ->badge()
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'likert_scale' => 'Skala Likert',
                        'text' => 'Teks',
                        default => $state,
                    })
                    ->colors([
                        'primary' => 'likert_scale',
                        'success' => 'text',
                    ]),
                Tables\Columns\IconColumn::make('is_required')
                    ->label('Wajib')
                    ->boolean(),
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
                Tables\Filters\SelectFilter::make('template_id')
                    ->label('Template')
                    ->relationship('template', 'nama_template')
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('jenis')
                    ->label('Jenis Pertanyaan')
                    ->options([
                        'likert_scale' => 'Skala Likert',
                        'text' => 'Teks',
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
            ->defaultSort('template_id')
            ->defaultSort('urutan', 'asc');
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
            'index' => Pages\ListEdomPertanyaans::route('/'),
            'create' => Pages\CreateEdomPertanyaan::route('/create'),
            'edit' => Pages\EditEdomPertanyaan::route('/{record}/edit'),
        ];
    }
}
