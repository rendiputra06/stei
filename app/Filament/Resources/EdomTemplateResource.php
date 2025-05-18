<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EdomTemplateResource\Pages;
use App\Filament\Resources\EdomTemplateResource\RelationManagers;
use App\Models\EdomTemplate;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EdomTemplateResource extends Resource
{
    protected static ?string $model = EdomTemplate::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationGroup = 'Evaluasi Dosen';

    protected static ?int $navigationSort = 1;

    protected static ?string $pluralModelLabel = 'Template Evaluasi';

    protected static ?string $modelLabel = 'Template Evaluasi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Template')
                    ->schema([
                        Forms\Components\TextInput::make('nama_template')
                            ->label('Nama Template')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('deskripsi')
                            ->label('Deskripsi')
                            ->maxLength(65535)
                            ->columnSpanFull(),
                        Forms\Components\Toggle::make('is_aktif')
                            ->label('Status Aktif')
                            ->default(true)
                            ->required(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_template')
                    ->label('Nama Template')
                    ->searchable(),
                Tables\Columns\TextColumn::make('deskripsi')
                    ->label('Deskripsi')
                    ->limit(50)
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_aktif')
                    ->label('Status')
                    ->boolean(),
                Tables\Columns\TextColumn::make('pertanyaan_count')
                    ->label('Jumlah Pertanyaan')
                    ->counts('pertanyaan'),
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
                Tables\Filters\SelectFilter::make('is_aktif')
                    ->label('Status')
                    ->options([
                        '1' => 'Aktif',
                        '0' => 'Tidak Aktif',
                    ])
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

    public static function getRelations(): array
    {
        return [
            RelationManagers\PertanyaanRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEdomTemplates::route('/'),
            'create' => Pages\CreateEdomTemplate::route('/create'),
            'edit' => Pages\EditEdomTemplate::route('/{record}/edit'),
        ];
    }
}
