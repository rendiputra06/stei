<?php

namespace App\Filament\Dosen\Resources\JadwalDosenResource\Pages;

use App\Filament\Dosen\Resources\JadwalDosenResource;
use App\Models\Jadwal;
use App\Models\KRSDetail;
use App\Models\Nilai;
use App\Models\PresensiDetail;
use Filament\Actions\Action;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Resources\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action as TableAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class ManageNilai extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string $resource = JadwalDosenResource::class;

    protected static string $view = 'filament.dosen.resources.jadwal-dosen-resource.pages.manage-nilai';

    public Jadwal $record;

    public function mount(Jadwal $record): void
    {
        $this->record = $record;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                KRSDetail::query()
                    ->where('jadwal_id', $this->record->id)
                    ->where('status', 'aktif')
                    ->with(['krs.mahasiswa', 'nilai'])
            )
            ->columns([
                TextColumn::make('krs.mahasiswa.nim')
                    ->label('NIM')
                    ->searchable(),
                TextColumn::make('krs.mahasiswa.nama')
                    ->label('Nama Mahasiswa')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('kehadiran')
                    ->label('Kehadiran (%)')
                    ->getStateUsing(function ($record) {
                        return $this->hitungPersentaseKehadiran($record);
                    }),
                TextColumn::make('nilai.nilai_tugas')
                    ->label('Tugas')
                    ->formatStateUsing(fn($state) => $state ? number_format($state, 2) : '-'),
                TextColumn::make('nilai.nilai_uts')
                    ->label('UTS')
                    ->formatStateUsing(fn($state) => $state ? number_format($state, 2) : '-'),
                TextColumn::make('nilai.nilai_uas')
                    ->label('UAS')
                    ->formatStateUsing(fn($state) => $state ? number_format($state, 2) : '-'),
                TextColumn::make('nilai.nilai_akhir')
                    ->label('Nilai Akhir')
                    ->formatStateUsing(fn($state) => $state ? number_format($state, 2) : '-'),
                TextColumn::make('nilai.grade')
                    ->label('Grade')
                    ->formatStateUsing(fn($state) => $state ?: '-'),
            ])
            ->actions([
                TableAction::make('input_nilai')
                    ->label('Input Nilai')
                    ->icon('heroicon-o-pencil')
                    ->form(function (KRSDetail $record) {
                        // Hitung nilai kehadiran
                        $nilaiKehadiran = $this->hitungPersentaseKehadiran($record);

                        // Dapatkan nilai jika sudah ada
                        $nilai = $record->nilai ?? new Nilai();

                        return [
                            Section::make('Informasi Mahasiswa')
                                ->schema([
                                    TextInput::make('nim')
                                        ->label('NIM')
                                        ->default($record->krs->mahasiswa->nim)
                                        ->disabled(),
                                    TextInput::make('nama')
                                        ->label('Nama Mahasiswa')
                                        ->default($record->krs->mahasiswa->nama)
                                        ->disabled(),
                                    TextInput::make('nilai_kehadiran')
                                        ->label('Nilai Kehadiran')
                                        ->default($nilaiKehadiran)
                                        ->disabled()
                                        ->suffix('%'),
                                ])->columns(3),

                            Section::make('Input Nilai')
                                ->schema([
                                    TextInput::make('nilai_tugas')
                                        ->label('Nilai Tugas')
                                        ->numeric()
                                        ->minValue(0)
                                        ->maxValue(100)
                                        ->step(0.01)
                                        ->default($nilai->nilai_tugas),
                                    TextInput::make('nilai_uts')
                                        ->label('Nilai UTS')
                                        ->numeric()
                                        ->minValue(0)
                                        ->maxValue(100)
                                        ->step(0.01)
                                        ->default($nilai->nilai_uts),
                                    TextInput::make('nilai_uas')
                                        ->label('Nilai UAS')
                                        ->numeric()
                                        ->minValue(0)
                                        ->maxValue(100)
                                        ->step(0.01)
                                        ->default($nilai->nilai_uas),
                                ])->columns(3),

                            Section::make('Hasil Akhir')
                                ->schema([
                                    TextInput::make('nilai_akhir')
                                        ->label('Nilai Akhir')
                                        ->disabled()
                                        ->default($nilai->nilai_akhir),
                                    TextInput::make('grade')
                                        ->label('Grade')
                                        ->disabled()
                                        ->default($nilai->grade),
                                    Textarea::make('keterangan')
                                        ->label('Keterangan')
                                        ->default($nilai->keterangan)
                                        ->rows(2),
                                ])->columns(3),
                        ];
                    })
                    ->action(function (KRSDetail $record, array $data) {
                        // Hitung nilai kehadiran
                        $nilaiKehadiran = $this->hitungPersentaseKehadiran($record);

                        // Buat atau update nilai
                        $nilai = Nilai::updateOrCreate(
                            ['krs_detail_id' => $record->id],
                            [
                                'nilai_tugas' => $data['nilai_tugas'],
                                'nilai_uts' => $data['nilai_uts'],
                                'nilai_uas' => $data['nilai_uas'],
                                'nilai_kehadiran' => $nilaiKehadiran,
                                'keterangan' => $data['keterangan'],
                            ]
                        );

                        // Hitung nilai akhir dan grade
                        $nilai->updateNilaiAkhirDanGrade();
                    }),
            ])
            ->headerActions([
                TableAction::make('hitung_nilai_kehadiran')
                    ->label('Hitung Nilai Kehadiran')
                    ->icon('heroicon-o-calculator')
                    ->action(function () {
                        // Dapatkan semua KRS Detail untuk kelas ini
                        $krsDetails = KRSDetail::where('jadwal_id', $this->record->id)
                            ->where('status', 'aktif')
                            ->with(['krs.mahasiswa', 'nilai'])
                            ->get();

                        // Update nilai kehadiran untuk semua mahasiswa
                        foreach ($krsDetails as $krsDetail) {
                            $nilaiKehadiran = $this->hitungPersentaseKehadiran($krsDetail);

                            // Update atau buat nilai
                            $nilai = Nilai::updateOrCreate(
                                ['krs_detail_id' => $krsDetail->id],
                                ['nilai_kehadiran' => $nilaiKehadiran]
                            );

                            // Hitung nilai akhir dan grade jika sudah ada nilai lain
                            if ($nilai->nilai_tugas !== null && $nilai->nilai_uts !== null && $nilai->nilai_uas !== null) {
                                $nilai->updateNilaiAkhirDanGrade();
                            }
                        }

                        $this->notify('success', 'Nilai kehadiran berhasil dihitung untuk semua mahasiswa');
                    }),
            ]);
    }

    /**
     * Hitung persentase kehadiran mahasiswa
     */
    private function hitungPersentaseKehadiran(KRSDetail $krsDetail): float
    {
        $mahasiswaId = $krsDetail->krs->mahasiswa_id;
        $jadwalId = $krsDetail->jadwal_id;

        // Dapatkan semua presensi untuk jadwal ini
        $totalPertemuan = DB::table('presensi')
            ->where('jadwal_id', $jadwalId)
            ->count();

        if ($totalPertemuan === 0) {
            return 100; // Default jika belum ada presensi
        }

        // Hitung jumlah kehadiran
        $jumlahHadir = DB::table('presensi_detail')
            ->join('presensi', 'presensi_detail.presensi_id', '=', 'presensi.id')
            ->where('presensi.jadwal_id', $jadwalId)
            ->where('presensi_detail.mahasiswa_id', $mahasiswaId)
            ->where('presensi_detail.status', 'hadir')
            ->count();

        // Hitung persentase kehadiran
        $persentaseKehadiran = ($jumlahHadir / $totalPertemuan) * 100;

        return round($persentaseKehadiran, 2);
    }
}
