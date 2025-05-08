<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MataKuliahResource\Pages;
use App\Models\MataKuliah;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class MataKuliahResource extends Resource
{
    protected static ?string $model = MataKuliah::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Data Master';

    protected static ?int $navigationSort = 21;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Mata Kuliah')
                    ->schema([
                        Forms\Components\TextInput::make('kode')
                            ->required()
                            ->maxLength(20)
                            ->unique(ignoreRecord: true),
                        Forms\Components\TextInput::make('nama')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('program_studi_id')
                            ->relationship('programStudi', 'nama')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(fn(callable $set) => $set('kurikulum_id', null)),
                        Forms\Components\Select::make('kurikulum_id')
                            ->relationship('kurikulum', 'nama', function (Builder $query, callable $get) {
                                $programStudiId = $get('program_studi_id');
                                if ($programStudiId) {
                                    $query->where('program_studi_id', $programStudiId);
                                }
                            })
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\TextInput::make('sks')
                            ->required()
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(8),
                        Forms\Components\TextInput::make('semester')
                            ->required()
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(8),
                        Forms\Components\Select::make('jenis')
                            ->required()
                            ->options([
                                'wajib' => 'Wajib',
                                'pilihan' => 'Pilihan',
                            ])
                            ->default('wajib'),
                        Forms\Components\Toggle::make('is_active')
                            ->required()
                            ->default(true)
                            ->label('Status Aktif'),
                        Forms\Components\Textarea::make('deskripsi')
                            ->maxLength(65535)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
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
                Tables\Columns\TextColumn::make('programStudi.nama')
                    ->searchable()
                    ->sortable()
                    ->label('Program Studi'),
                Tables\Columns\TextColumn::make('kurikulum.nama')
                    ->searchable()
                    ->sortable()
                    ->label('Kurikulum'),
                Tables\Columns\TextColumn::make('sks')
                    ->sortable(),
                Tables\Columns\TextColumn::make('semester')
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('jenis')
                    ->colors([
                        'primary' => 'wajib',
                        'success' => 'pilihan',
                    ]),
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
                Tables\Filters\SelectFilter::make('kurikulum')
                    ->relationship('kurikulum', 'nama'),
                Tables\Filters\SelectFilter::make('semester')
                    ->options(array_combine(range(1, 8), range(1, 8))),
                Tables\Filters\SelectFilter::make('jenis')
                    ->options([
                        'wajib' => 'Wajib',
                        'pilihan' => 'Pilihan',
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
            'index' => Pages\ListMataKuliahs::route('/'),
            'create' => Pages\CreateMataKuliah::route('/create'),
            'edit' => Pages\EditMataKuliah::route('/{record}/edit'),
        ];
    }
}
