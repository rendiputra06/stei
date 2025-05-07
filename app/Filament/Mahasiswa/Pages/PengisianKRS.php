<?php

namespace App\Filament\Mahasiswa\Pages;

use App\Models\KRS;
use App\Models\KRSDetail;
use App\Models\Mahasiswa;
use App\Models\Jadwal;
use App\Models\TahunAkademik;
use App\Models\StatusMahasiswa;
use Filament\Pages\Page;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Grid;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Notifications\Notification;
use Filament\Actions\Action as PageAction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;

class PengisianKRS extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.mahasiswa.pages.pengisian-krs';

    protected static ?string $title = 'Pengisian KRS';

    protected static ?string $navigationLabel = 'Pengisian KRS';

    protected static ?int $navigationSort = 2;

    public $krs = null;
    public $tahunAkademik = null;
    public $mahasiswa = null;
    public $statusMahasiswa = null;

    public function mount(): void
    {
        // Ambil data mahasiswa berdasarkan user yang login
        $this->mahasiswa = Mahasiswa::where('user_id', Auth::id())->first();
        if (!$this->mahasiswa) {
            Notification::make()
                ->title('Error: Data mahasiswa tidak ditemukan')
                ->danger()
                ->send();

            return;
        }

        // Ambil tahun akademik aktif
        $this->tahunAkademik = TahunAkademik::getAktif();

        if (!$this->tahunAkademik) {
            Notification::make()
                ->title('Error: Tidak ada tahun akademik aktif')
                ->danger()
                ->send();

            return;
        }

        // Ambil status mahasiswa pada tahun akademik ini
        $this->statusMahasiswa = StatusMahasiswa::where('mahasiswa_id', $this->mahasiswa->id)
            ->where('tahun_akademik_id', $this->tahunAkademik->id)
            ->first();

        // Cek apakah KRS untuk mahasiswa di tahun akademik ini sudah ada
        $this->krs = KRS::where('mahasiswa_id', $this->mahasiswa->id)
            ->where('tahun_akademik_id', $this->tahunAkademik->id)
            ->first();

        // Jika belum ada KRS, buat KRS baru
        if (!$this->krs && $this->dapatMembuatKRS()) {
            $semester = StatusMahasiswa::hitungSemester($this->mahasiswa, $this->tahunAkademik);

            $this->krs = KRS::create([
                'mahasiswa_id' => $this->mahasiswa->id,
                'tahun_akademik_id' => $this->tahunAkademik->id,
                'status' => 'draft',
                'semester' => $semester,
            ]);

            Notification::make()
                ->title('KRS baru dibuat untuk ' . $this->tahunAkademik->nama)
                ->success()
                ->send();
        }
    }

    protected function dapatMembuatKRS(): bool
    {
        // Cek jika tahun akademik aktif
        if (!$this->tahunAkademik) {
            return false;
        }

        // Cek jika saat ini adalah periode pengisian KRS
        $now = Carbon::now();
        if (!$now->between($this->tahunAkademik->krs_mulai, $this->tahunAkademik->krs_selesai)) {
            return false;
        }

        // Cek jika status mahasiswa adalah aktif
        if (!$this->statusMahasiswa || $this->statusMahasiswa->status !== 'aktif') {
            return false;
        }

        return true;
    }

    public function getTableQuery(): Builder
    {
        // Jika tidak ada KRS, kembalikan query kosong
        if (!$this->krs) {
            return KRSDetail::query()->where('id', 0);
        }

        return KRSDetail::query()
            ->where('krs_id', $this->krs->id);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())
            ->columns([
                TextColumn::make('mataKuliah.kode')
                    ->label('Kode MK')
                    ->searchable(),
                TextColumn::make('mataKuliah.nama')
                    ->label('Mata Kuliah')
                    ->searchable(),
                TextColumn::make('sks')
                    ->label('SKS'),
                TextColumn::make('jadwal.hari')
                    ->label('Hari'),
                TextColumn::make('jadwal.jam_mulai')
                    ->label('Jam Mulai')
                    ->formatStateUsing(fn(Carbon $state) => $state->format('H:i')),
                TextColumn::make('jadwal.jam_selesai')
                    ->label('Jam Selesai')
                    ->formatStateUsing(fn(Carbon $state) => $state->format('H:i')),
                TextColumn::make('jadwal.ruangan.nama')
                    ->label('Ruangan'),
                TextColumn::make('jadwal.dosen.nama')
                    ->label('Dosen'),
                TextColumn::make('kelas')
                    ->label('Kelas'),
                TextColumn::make('status')
                    ->label('Status')
            ])
            ->actions([
                DeleteAction::make()
                    ->visible(fn($record) => $this->krs && $this->krs->isDraftStatus() && $this->dapatMembuatKRS())
                    ->using(function ($record) {
                        $krs = $record->krs;
                        $record->delete();
                        $krs->updateTotalSKS();

                        Notification::make()
                            ->title('Mata kuliah berhasil dihapus dari KRS')
                            ->success()
                            ->send();
                    })
            ])
            ->emptyStateHeading('Belum ada mata kuliah yang dipilih')
            ->emptyStateDescription('Silakan pilih mata kuliah yang ingin diambil dari daftar mata kuliah yang ditawarkan.')
            ->paginated(false);
    }

    protected function getHeaderActions(): array
    {
        return [
            PageAction::make('pilih_mata_kuliah')
                ->label('Pilih Mata Kuliah')
                ->modalHeading('Pilih Mata Kuliah yang Ditawarkan')
                ->modalDescription('Silakan pilih mata kuliah yang ingin diambil')
                ->visible(fn() => $this->krs && $this->krs->isDraftStatus() && $this->dapatMembuatKRS())
                ->action(function (array $data): void {
                    $jadwal = Jadwal::findOrFail($data['jadwal_id']);

                    // Cek jika mata kuliah sudah dipilih
                    $exists = KRSDetail::where('krs_id', $this->krs->id)
                        ->where('mata_kuliah_id', $jadwal->mata_kuliah_id)
                        ->exists();

                    if ($exists) {
                        Notification::make()
                            ->title('Mata kuliah ini sudah dipilih')
                            ->danger()
                            ->send();

                        return;
                    }

                    // Cek jika jadwal bentrok
                    $bentrok = $this->cekJadwalBentrok($jadwal);
                    if ($bentrok) {
                        Notification::make()
                            ->title('Jadwal bentrok dengan mata kuliah yang sudah dipilih')
                            ->danger()
                            ->send();

                        return;
                    }

                    // Buat KRS Detail
                    KRSDetail::create([
                        'krs_id' => $this->krs->id,
                        'jadwal_id' => $jadwal->id,
                        'mata_kuliah_id' => $jadwal->mata_kuliah_id,
                        'sks' => $jadwal->mataKuliah->sks,
                        'kelas' => $jadwal->kelas,
                        'status' => 'aktif',
                    ]);

                    // Update total SKS
                    $this->krs->updateTotalSKS();

                    Notification::make()
                        ->title('Mata kuliah berhasil ditambahkan ke KRS')
                        ->success()
                        ->send();
                })
                ->form([
                    Select::make('jadwal_id')
                        ->label('Mata Kuliah')
                        ->options(function () {
                            // Ambil daftar jadwal yang tersedia
                            return Jadwal::with(['mataKuliah', 'dosen', 'ruangan'])
                                ->where('tahun_akademik_id', $this->tahunAkademik->id)
                                ->where('is_active', true)
                                ->whereHas('mataKuliah', function ($query) {
                                    // Filter mata kuliah sesuai kurikulum yang sedang aktif untuk program studi mahasiswa
                                    $query->where('program_studi_id', $this->mahasiswa->program_studi_id)
                                        ->where('is_active', true)
                                        ->whereHas('kurikulum', function ($q) {
                                            $q->where('is_active', true);
                                        });
                                })
                                ->get()
                                ->mapWithKeys(function ($jadwal) {
                                    $label = $jadwal->mataKuliah->kode . ' - ' . $jadwal->mataKuliah->nama .
                                        ' (' . $jadwal->mataKuliah->sks . ' SKS) - ' .
                                        $jadwal->hari . ' ' . $jadwal->jam_mulai->format('H:i') . '-' . $jadwal->jam_selesai->format('H:i') .
                                        ' - Dosen: ' . $jadwal->dosen->nama;

                                    return [$jadwal->id => $label];
                                });
                        })
                        ->searchable()
                        ->required()
                ]),
            PageAction::make('submit_krs')
                ->label('Submit KRS')
                ->color('success')
                ->visible(fn() => $this->krs && $this->krs->isDraftStatus() && $this->dapatMembuatKRS())
                ->requiresConfirmation()
                ->modalHeading('Submit KRS')
                ->modalDescription('Apakah Anda yakin ingin submit KRS? KRS yang sudah disubmit tidak dapat diubah lagi.')
                ->action(function (): void {
                    // Cek jika ada mata kuliah yang dipilih
                    $detailCount = KRSDetail::where('krs_id', $this->krs->id)->count();

                    if ($detailCount === 0) {
                        Notification::make()
                            ->title('Tidak ada mata kuliah yang dipilih')
                            ->danger()
                            ->send();

                        return;
                    }

                    // Update status KRS
                    $this->krs->update([
                        'status' => 'submitted',
                        'tanggal_submit' => now(),
                    ]);

                    Notification::make()
                        ->title('KRS berhasil disubmit')
                        ->success()
                        ->send();
                })
        ];
    }

    protected function cekJadwalBentrok($jadwal): bool
    {
        // Ambil jadwal mata kuliah yang sudah dipilih
        $jadwalDipilih = KRSDetail::where('krs_id', $this->krs->id)
            ->with('jadwal')
            ->get()
            ->map(function ($detail) {
                return $detail->jadwal;
            });

        foreach ($jadwalDipilih as $existing) {
            // Jika hari sama, periksa jam bentrok
            if ($existing->hari === $jadwal->hari) {
                // Bentrok jika:
                // 1. Jadwal baru mulai di tengah jadwal lama
                // 2. Jadwal baru selesai di tengah jadwal lama
                // 3. Jadwal baru mencakup jadwal lama

                $newStart = $jadwal->jam_mulai;
                $newEnd = $jadwal->jam_selesai;
                $existingStart = $existing->jam_mulai;
                $existingEnd = $existing->jam_selesai;

                if (
                    ($newStart >= $existingStart && $newStart < $existingEnd) ||
                    ($newEnd > $existingStart && $newEnd <= $existingEnd) ||
                    ($newStart <= $existingStart && $newEnd >= $existingEnd)
                ) {
                    return true;
                }
            }
        }

        return false;
    }
}
