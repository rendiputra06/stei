<?php

namespace App\Filament\Dosen\Resources;

use App\Filament\Dosen\Resources\JadwalDosenResource\Pages;
use App\Models\Dosen;
use App\Models\Jadwal;
use App\Models\TahunAkademik;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class JadwalDosenResource extends Resource
{
    protected static ?string $model = Jadwal::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $navigationLabel = 'Jadwal Mengajar';

    protected static ?string $pluralModelLabel = 'Jadwal Mengajar';

    protected static ?string $modelLabel = 'Jadwal Mengajar';

    protected static ?int $navigationSort = 10;

    public static function getEloquentQuery(): Builder
    {
        // Ambil tahun akademik yang sedang aktif
        $tahunAkademikAktif = TahunAkademik::getAktif();

        // Ambil data dosen berdasarkan user_id dari pengguna yang login
        $dosen = Dosen::where('user_id', Auth::id())->first();

        // Query dasar
        $query = parent::getEloquentQuery()
            ->where('is_active', true)
            ->orderBy('hari');

        // Filter berdasarkan dosen yang login
        if ($dosen) {
            $query->where('dosen_id', $dosen->id);
        } else {
            // Fallback jika dosen tidak ditemukan
            $query->where('id', 0); // Query yang tidak mengembalikan hasil
        }

        // Filter berdasarkan tahun akademik aktif jika ada
        if ($tahunAkademikAktif) {
            $query->where('tahun_akademik_id', $tahunAkademikAktif->id);
        }

        return $query;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Jadwal')
                    ->schema([
                        Forms\Components\TextInput::make('mataKuliah.nama')
                            ->label('Mata Kuliah')
                            ->disabled(),
                        Forms\Components\TextInput::make('mataKuliah.kode')
                            ->label('Kode Mata Kuliah')
                            ->disabled(),
                        Forms\Components\TextInput::make('mataKuliah.sks')
                            ->label('SKS')
                            ->disabled(),
                        Forms\Components\TextInput::make('tahunAkademik.nama')
                            ->label('Tahun Akademik')
                            ->disabled(),
                        Forms\Components\TextInput::make('hari')
                            ->disabled(),
                        Forms\Components\TextInput::make('jam_mulai')
                            ->label('Jam Mulai')
                            ->disabled(),
                        Forms\Components\TextInput::make('jam_selesai')
                            ->label('Jam Selesai')
                            ->disabled(),
                        Forms\Components\TextInput::make('ruangan.nama')
                            ->label('Ruangan')
                            ->disabled(),
                        Forms\Components\TextInput::make('kelas')
                            ->disabled(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('mataKuliah.nama')
                    ->label('Mata Kuliah')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('mataKuliah.kode')
                    ->label('Kode')
                    ->searchable(),
                Tables\Columns\TextColumn::make('hari')
                    ->sortable(),
                Tables\Columns\TextColumn::make('jam_mulai')
                    ->label('Jam')
                    ->formatStateUsing(fn($state, $record) => $state->format('H:i') . ' - ' . $record->jam_selesai->format('H:i')),
                Tables\Columns\TextColumn::make('ruangan.nama')
                    ->label('Ruangan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('kelas')
                    ->searchable(),
                Tables\Columns\TextColumn::make('jumlahMahasiswa')
                    ->label('Jumlah Mahasiswa')
                    ->getStateUsing(fn($record) => $record->jumlahMahasiswa()),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\Action::make('presensi')
                    ->label('Presensi')
                    ->icon('heroicon-o-clipboard-document-check')
                    ->url(fn(Jadwal $record) => JadwalDosenResource::getUrl('presensi', ['record' => $record])),
                Tables\Actions\Action::make('materi')
                    ->label('Materi')
                    ->icon('heroicon-o-document-text')
                    ->url(fn(Jadwal $record) => JadwalDosenResource::getUrl('materi', ['record' => $record])),
                Tables\Actions\Action::make('nilai')
                    ->label('Nilai')
                    ->icon('heroicon-o-clipboard-document-list')
                    ->url(fn(Jadwal $record) => JadwalDosenResource::getUrl('nilai', ['record' => $record])),
            ])
            ->bulkActions([
                //
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListJadwalDosens::route('/'),
            'view' => Pages\ViewJadwalDosen::route('/{record}'),
            'presensi' => Pages\ManagePresensi::route('/{record}/presensi'),
            'materi' => Pages\ManageMateri::route('/{record}/materi'),
            'nilai' => Pages\ManageNilai::route('/{record}/nilai'),
        ];
    }
}
