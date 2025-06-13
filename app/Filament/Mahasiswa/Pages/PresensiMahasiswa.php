<?php

namespace App\Filament\Mahasiswa\Pages;

use App\Models\Jadwal;
use App\Models\KRS;
use App\Models\KRSDetail;
use App\Models\Mahasiswa;
use App\Models\Presensi;
use App\Models\PresensiDetail;
use App\Models\TahunAkademik;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PresensiMahasiswa extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static string $view = 'filament.mahasiswa.pages.presensi-mahasiswa';

    protected static ?string $title = 'Presensi Mahasiswa';

    protected static ?string $navigationLabel = 'Presensi';

    protected static ?int $navigationSort = 5;

    // Properties
    public $mahasiswa = null;
    public $selectedJadwalId = null;
    public $selectedSemester = null;
    public $selectedTahunAkademikId = null;
    public $mataKuliahList = [];
    public $persentaseKehadiran = 0;
    public $totalPertemuan = 0;
    public $totalHadir = 0;
    public $jadwalDetail = null;

    // Filter properties
    public ?array $data = [];

    public function mount(): void
    {
        // Ambil data mahasiswa berdasarkan user yang login
        $this->mahasiswa = Mahasiswa::where('user_id', Auth::id())->first();

        if (!$this->mahasiswa) {
            return;
        }

        // Ambil tahun akademik yang aktif
        $tahunAkademikAktif = TahunAkademik::where('aktif', true)->first();
        if ($tahunAkademikAktif) {
            $this->selectedTahunAkademikId = $tahunAkademikAktif->id;
            $this->data['tahun_akademik_id'] = $tahunAkademikAktif->id;
        }

        // Ambil jadwal mata kuliah mahasiswa
        $this->refreshMataKuliahList();
    }

    public function refreshMataKuliahList(): void
    {
        if (!$this->mahasiswa || !$this->selectedTahunAkademikId) {
            $this->mataKuliahList = [];
            return;
        }

        // Ambil daftar mata kuliah yang diambil pada tahun akademik yang dipilih
        $this->mataKuliahList = KRSDetail::join('krs', 'krs_detail.krs_id', '=', 'krs.id')
            ->join('jadwal', 'krs_detail.jadwal_id', '=', 'jadwal.id')
            ->join('mata_kuliah', 'jadwal.mata_kuliah_id', '=', 'mata_kuliah.id')
            ->where('krs.mahasiswa_id', $this->mahasiswa->id)
            ->where('jadwal.tahun_akademik_id', $this->selectedTahunAkademikId)
            ->where('krs_detail.status', 'aktif')
            ->select('jadwal.id', 'mata_kuliah.nama', 'mata_kuliah.kode')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->id => $item->kode . ' - ' . $item->nama];
            })
            ->toArray();

        // Set default jadwal jika ada
        if (!empty($this->mataKuliahList)) {
            $keys = array_keys($this->mataKuliahList);
            $this->selectedJadwalId = reset($keys);
            $this->data['jadwal_id'] = $this->selectedJadwalId;

            $this->refreshJadwalDetail();
            $this->calculateKehadiran();
        }
    }

    public function refreshJadwalDetail(): void
    {
        if (!$this->selectedJadwalId) {
            $this->jadwalDetail = null;
            return;
        }

        $this->jadwalDetail = Jadwal::with(['mataKuliah', 'dosen', 'tahunAkademik', 'ruangan.gedung'])
            ->find($this->selectedJadwalId);
    }

    public function calculateKehadiran(): void
    {
        if (!$this->mahasiswa || !$this->selectedJadwalId) {
            $this->persentaseKehadiran = 0;
            $this->totalPertemuan = 0;
            $this->totalHadir = 0;
            return;
        }

        // Hitung total pertemuan
        $this->totalPertemuan = Presensi::where('jadwal_id', $this->selectedJadwalId)->count();

        // Hitung total kehadiran
        $this->totalHadir = PresensiDetail::join('presensi', 'presensi_detail.presensi_id', '=', 'presensi.id')
            ->where('presensi.jadwal_id', $this->selectedJadwalId)
            ->where('presensi_detail.mahasiswa_id', $this->mahasiswa->id)
            ->where('presensi_detail.status', 'hadir')
            ->count();

        // Hitung persentase kehadiran
        $this->persentaseKehadiran = $this->totalPertemuan > 0
            ? round(($this->totalHadir / $this->totalPertemuan) * 100, 2)
            : 0;
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Filter Presensi')
                    ->schema([
                        Grid::make(1)
                            ->schema([
                                Select::make('semester')
                                    ->label('Semester')
                                    ->options(function () {
                                        if (!$this->mahasiswa) {
                                            return [];
                                        }

                                        // Ambil semester-semester yang memiliki KRS dengan nilai
                                        return KRS::where('mahasiswa_id', $this->mahasiswa->id)
                                            ->whereHas('krsDetail.nilai')
                                            ->orderBy('semester')
                                            ->pluck('semester', 'semester')
                                            ->toArray();
                                    })
                                    ->reactive()
                                    ->afterStateUpdated(function ($state) {
                                        if ($state) {
                                            $this->selectedSemester = $state;

                                            // Update tahun akademik yang sesuai dengan semester yang dipilih
                                            $krs = KRS::where('mahasiswa_id', $this->mahasiswa->id)
                                                ->where('semester', $state)
                                                ->first();

                                            if ($krs) {
                                                $this->selectedTahunAkademikId = $krs->tahun_akademik_id;
                                                $this->data['tahun_akademik_id'] = $krs->tahun_akademik_id;
                                            }
                                        }
                                    }),

                                Select::make('tahun_akademik_id')
                                    ->label('Tahun Akademik')
                                    ->options(function () {
                                        if (!$this->mahasiswa) {
                                            return [];
                                        }

                                        return TahunAkademik::whereHas('krs', function ($query) {
                                            $query->where('mahasiswa_id', $this->mahasiswa->id)
                                                ->whereHas('krsDetail.nilai');
                                        })
                                            ->orderBy('tahun')
                                            ->orderBy('semester')
                                            ->pluck('nama', 'id')
                                            ->toArray();
                                    })
                                    ->disabled(),

                                Select::make('jadwal_id')
                                    ->label('Mata Kuliah')
                                    ->options($this->mataKuliahList)
                                    ->reactive()
                                    ->afterStateUpdated(function ($state) {
                                        if ($state) {
                                            $this->selectedJadwalId = $state;
                                            $this->refreshJadwalDetail();
                                            $this->calculateKehadiran();
                                        }
                                    }),
                            ])
                    ])
            ])
            ->statePath('data');
    }

    public function getTableQuery(): Builder
    {
        // Default query kosong jika belum ada jadwal yang dipilih
        if (!$this->selectedJadwalId || !$this->mahasiswa) {
            return Presensi::query()->where('id', 0);
        }

        return Presensi::query()
            ->where('jadwal_id', $this->selectedJadwalId)
            ->with(['presensiDetail' => function ($query) {
                $query->where('mahasiswa_id', $this->mahasiswa->id);
            }])
            ->orderBy('pertemuan_ke');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())
            ->columns([
                TextColumn::make('pertemuan_ke')
                    ->label('Pertemuan Ke')
                    ->sortable(),
                TextColumn::make('tanggal')
                    ->label('Tanggal')
                    ->date('d F Y')
                    ->sortable(),
                TextColumn::make('keterangan')
                    ->label('Materi/Keterangan')
                    ->limit(50),
                TextColumn::make('presensiDetail.status')
                    ->label('Status Kehadiran')
                    ->formatStateUsing(function ($state, $record) {
                        if (!$record->presensiDetail->count()) {
                            return 'Belum Direkam';
                        }

                        $status = $record->presensiDetail->first()->status ?? 'Belum Direkam';

                        $statusLabels = [
                            'hadir' => 'Hadir',
                            'izin' => 'Izin',
                            'sakit' => 'Sakit',
                            'alpa' => 'Tanpa Keterangan',
                        ];

                        return $statusLabels[$status] ?? 'Belum Direkam';
                    })
                    ->badge()
                    ->color(function ($state, $record) {
                        if (!$record->presensiDetail->count()) {
                            return 'gray';
                        }

                        $status = $record->presensiDetail->first()->status ?? '';

                        $statusColors = [
                            'hadir' => 'success',
                            'izin' => 'warning',
                            'sakit' => 'info',
                            'alpa' => 'danger',
                        ];

                        return $statusColors[$status] ?? 'gray';
                    }),
                TextColumn::make('presensiDetail.keterangan')
                    ->label('Keterangan Kehadiran')
                    ->formatStateUsing(function ($state, $record) {
                        return $record->presensiDetail->first()->keterangan ?? '-';
                    }),
            ])
            ->emptyStateHeading('Belum ada data presensi')
            ->emptyStateDescription('Belum ada data presensi untuk mata kuliah yang dipilih')
            ->paginated(false);
    }
}
