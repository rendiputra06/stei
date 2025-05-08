<?php

namespace App\Filament\Dosen\Resources;

use App\Filament\Dosen\Resources\MahasiswaBimbinganResource\Pages;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class MahasiswaBimbinganResource extends Resource
{
    protected static ?string $model = Mahasiswa::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationLabel = 'Mahasiswa Bimbingan';

    protected static ?string $pluralModelLabel = 'Mahasiswa Bimbingan';

    protected static ?string $modelLabel = 'Mahasiswa Bimbingan';

    protected static ?int $navigationSort = 30;

    public static function getEloquentQuery(): Builder
    {
        // Ambil data dosen berdasarkan user_id dari pengguna yang login
        $dosen = Dosen::where('user_id', Auth::id())->first();

        // Hanya tampilkan mahasiswa bimbingan untuk dosen yang login
        return parent::getEloquentQuery()
            ->when($dosen, function ($query) use ($dosen) {
                return $query->whereHas('pembimbingan', function ($subQuery) use ($dosen) {
                    $subQuery->where('dosen_id', $dosen->id);
                });
            }, function ($query) {
                // Fallback jika dosen tidak ditemukan
                return $query->where('id', 0); // Query yang tidak mengembalikan hasil
            });
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Data Mahasiswa')
                    ->schema([
                        Forms\Components\TextInput::make('nim')
                            ->label('NIM')
                            ->required()
                            ->maxLength(20)
                            ->disabled(),
                        Forms\Components\TextInput::make('nama')
                            ->required()
                            ->maxLength(255)
                            ->disabled(),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->disabled(),
                        Forms\Components\TextInput::make('no_telepon')
                            ->label('No. Telepon')
                            ->tel()
                            ->maxLength(20)
                            ->disabled(),
                        Forms\Components\Radio::make('jenis_kelamin')
                            ->label('Jenis Kelamin')
                            ->options([
                                'L' => 'Laki-laki',
                                'P' => 'Perempuan',
                            ])
                            ->disabled(),
                        Forms\Components\DatePicker::make('tanggal_lahir')
                            ->label('Tanggal Lahir')
                            ->disabled(),
                        Forms\Components\TextInput::make('programStudi.nama')
                            ->label('Program Studi')
                            ->disabled(),
                        Forms\Components\Textarea::make('alamat')
                            ->maxLength(65535)
                            ->columnSpanFull()
                            ->disabled(),
                    ])
                    ->columns(2)
                    ->collapsible(),

                Forms\Components\Section::make('Status Akademik')
                    ->schema([
                        Forms\Components\TextInput::make('semester_aktif')
                            ->label('Semester Aktif')
                            ->numeric()
                            ->disabled(),
                        Forms\Components\TextInput::make('statusMahasiswa.nama')
                            ->label('Status')
                            ->disabled(),
                        Forms\Components\TextInput::make('ipk')
                            ->label('IPK')
                            ->numeric()
                            ->disabled(),
                        Forms\Components\TextInput::make('total_sks')
                            ->label('Total SKS')
                            ->numeric()
                            ->disabled(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Dosen Pembimbing')
                    ->schema([
                        Forms\Components\TextInput::make('pembimbingan.dosen.nama')
                            ->label('Dosen Pembimbing Akademik')
                            ->disabled(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nim')
                    ->label('NIM')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nama')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('no_telepon')
                    ->label('No. Telepon')
                    ->searchable(),
                Tables\Columns\TextColumn::make('programStudi.nama')
                    ->label('Program Studi')
                    ->sortable(),
                Tables\Columns\TextColumn::make('semester_aktif')
                    ->label('Semester')
                    ->sortable(),
                Tables\Columns\TextColumn::make('ipk')
                    ->label('IPK')
                    ->formatStateUsing(fn($state) => $state ? number_format($state, 2) : '-')
                    ->sortable(),
                Tables\Columns\TextColumn::make('statusMahasiswa.nama')
                    ->label('Status')
                    ->sortable(),
            ])
            ->filters([
                // Filters tambahan bisa ditambahkan di sini
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\Action::make('lihat_krs')
                    ->label('Lihat KRS')
                    ->icon('heroicon-o-document-text')
                    ->url(fn(Mahasiswa $record) => route('filament.dosen.resources.krs-mahasiswas.index', ['tableSearch' => $record->nim])),
            ])
            ->bulkActions([
                // Tidak ada bulk actions
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
            'index' => Pages\ListMahasiswaBimbingans::route('/'),
            'view' => Pages\ViewMahasiswaBimbingan::route('/{record}'),
        ];
    }
}
