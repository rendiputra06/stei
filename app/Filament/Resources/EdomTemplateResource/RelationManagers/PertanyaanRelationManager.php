<?php

namespace App\Filament\Resources\EdomTemplateResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PertanyaanRelationManager extends RelationManager
{
    protected static string $relationship = 'pertanyaan';

    protected static ?string $recordTitleAttribute = 'pertanyaan';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
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
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('pertanyaan')
            ->columns([
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
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
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
            ->defaultSort('urutan', 'asc');
    }
}
