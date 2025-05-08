<?php

namespace App\Filament\Dosen\Resources;

use App\Filament\Dosen\Resources\KrsMahasiswaResource\Pages;
use App\Models\Dosen;
use App\Models\KRS;
use App\Models\KRSDetail;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class KrsMahasiswaResource extends Resource
{
    protected static ?string $model = KRS::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-check';

    protected static ?string $navigationLabel = 'Persetujuan KRS';

    protected static ?string $pluralModelLabel = 'Persetujuan KRS';

    protected static ?string $modelLabel = 'KRS Mahasiswa';

    protected static ?int $navigationSort = 20;

    // Pastikan resource ini muncul di navigasi
    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }

    public static function getEloquentQuery(): Builder
    {
        // Ambil data dosen berdasarkan user_id dari pengguna yang login
        $dosen = Dosen::where('user_id', Auth::id())->first();

        // Hanya tampilkan KRS mahasiswa bimbingan
        return parent::getEloquentQuery()
            ->when($dosen, function ($query) use ($dosen) {
                return $query->whereHas('mahasiswa', function ($subQuery) use ($dosen) {
                    $subQuery->whereHas('pembimbingan', function ($pembimbinganQuery) use ($dosen) {
                        $pembimbinganQuery->where('dosen_id', $dosen->id);
                    });
                });
            }, function ($query) {
                // Fallback jika dosen tidak ditemukan
                return $query->where('id', 0); // Query yang tidak mengembalikan hasil
            })
            ->where('status', '!=', 'draft'); // Tidak perlu menampilkan draft
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi KRS')
                    ->schema([
                        Forms\Components\TextInput::make('mahasiswa.nama')
                            ->label('Nama Mahasiswa')
                            ->disabled(),
                        Forms\Components\TextInput::make('mahasiswa.nim')
                            ->label('NIM')
                            ->disabled(),
                        Forms\Components\TextInput::make('tahunAkademik.nama')
                            ->label('Tahun Akademik')
                            ->disabled(),
                        Forms\Components\TextInput::make('semester')
                            ->disabled(),
                        Forms\Components\TextInput::make('total_sks')
                            ->label('Total SKS')
                            ->disabled(),
                        Forms\Components\Radio::make('status')
                            ->label('Status')
                            ->options(KRS::getStatusList())
                            ->required()
                            ->disabled(fn(KRS $record) => $record->isApproved() || $record->isRejected()),
                        Forms\Components\Textarea::make('catatan_dosen')
                            ->label('Catatan Dosen')
                            ->rows(3)
                            ->disabled(fn(KRS $record) => $record->isApproved() || $record->isRejected()),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Daftar Mata Kuliah')
                    ->schema([
                        Forms\Components\Repeater::make('krsDetail')
                            ->label('')
                            ->schema([
                                Forms\Components\TextInput::make('mataKuliah.kode')
                                    ->label('Kode')
                                    ->disabled(),
                                Forms\Components\TextInput::make('mataKuliah.nama')
                                    ->label('Mata Kuliah')
                                    ->disabled(),
                                Forms\Components\TextInput::make('sks')
                                    ->label('SKS')
                                    ->disabled(),
                                Forms\Components\TextInput::make('jadwal.hari')
                                    ->label('Hari')
                                    ->disabled(),
                                Forms\Components\TextInput::make('jadwal.jam_mulai')
                                    ->label('Jam')
                                    ->formatStateUsing(fn($state, $record) => $state ? $state->format('H:i') . ' - ' . $record->jadwal->jam_selesai->format('H:i') : '-')
                                    ->disabled(),
                                Forms\Components\TextInput::make('kelas')
                                    ->disabled(),
                                Forms\Components\TextInput::make('jadwal.dosen.nama')
                                    ->label('Dosen')
                                    ->disabled(),
                            ])
                            ->itemLabel(fn(array $state): ?string => $state['mataKuliah.nama'] ?? null)
                            ->collapsed()
                            ->columns(4)
                            ->disabled(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('mahasiswa.nim')
                    ->label('NIM')
                    ->searchable(),
                Tables\Columns\TextColumn::make('mahasiswa.nama')
                    ->label('Nama Mahasiswa')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('semester')
                    ->sortable(),
                Tables\Columns\TextColumn::make('tahunAkademik.nama')
                    ->label('Tahun Akademik')
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_sks')
                    ->label('Total SKS')
                    ->sortable(),
                Tables\Columns\SelectColumn::make('status')
                    ->options(KRS::getStatusList())
                    ->disabled(),
                Tables\Columns\TextColumn::make('tanggal_submit')
                    ->label('Tanggal Pengajuan')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('tanggal_approval')
                    ->label('Tanggal Persetujuan')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(KRS::getStatusList()),
                Tables\Filters\SelectFilter::make('tahun_akademik_id')
                    ->relationship('tahunAkademik', 'nama')
                    ->label('Tahun Akademik'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make()
                    ->label('Setujui KRS')
                    ->hidden(fn(KRS $record) => $record->isApproved() || $record->isRejected()),
                Tables\Actions\Action::make('print')
                    ->label('Cetak KRS')
                    ->icon('heroicon-o-printer')
                    ->url(fn(KRS $record) => route('krs.print', $record))
                    ->openUrlInNewTab(),
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
            'index' => Pages\ListKrsMahasiswas::route('/'),
            'view' => Pages\ViewKrsMahasiswa::route('/{record}'),
            'edit' => Pages\EditKrsMahasiswa::route('/{record}/edit'),
        ];
    }
}
