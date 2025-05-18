<?php

namespace App\Filament\Mahasiswa\Pages;

use App\Models\KRS;
use App\Models\KRSDetail;
use App\Models\Mahasiswa;
use App\Models\TahunAkademik;
use Filament\Actions\Action;
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

class KartuHasilStudi extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static string $view = 'filament.mahasiswa.pages.kartu-hasil-studi';

    protected static ?string $title = 'Kartu Hasil Studi';

    protected static ?string $navigationLabel = 'Kartu Hasil Studi';

    protected static ?int $navigationSort = 3;

    // Properties
    public $mahasiswa = null;
    public $selectedSemester = null;
    public $selectedTahunAkademikId = null;
    public $ipSemester = 0;
    public $totalSKS = 0;
    public $totalNilai = 0;

    // Filter properties
    public ?array $data = [];

    public function mount(): void
    {
        // Ambil data mahasiswa berdasarkan user yang login
        $this->mahasiswa = Mahasiswa::where('user_id', Auth::id())->first();

        if (!$this->mahasiswa) {
            return;
        }

        // Set default filter ke semester terakhir yang ada nilainya
        $lastKRS = KRS::where('mahasiswa_id', $this->mahasiswa->id)
            ->whereHas('krsDetail.nilai')
            ->orderByDesc('semester')
            ->first();

        if ($lastKRS) {
            $this->selectedSemester = $lastKRS->semester;
            $this->selectedTahunAkademikId = $lastKRS->tahun_akademik_id;
            $this->data['semester'] = $this->selectedSemester;
            $this->data['tahun_akademik_id'] = $this->selectedTahunAkademikId;
        }

        $this->calculateIPK();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Filter KHS')
                    ->schema([
                        Grid::make(2)
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

                                            $this->calculateIPK();
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
                                    ->disabled()
                            ])
                    ])
            ])
            ->statePath('data');
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('print_khs')
                ->label('Cetak KHS')
                ->icon('heroicon-o-printer')
                ->color('success')
                ->visible(fn() => $this->selectedSemester && $this->mahasiswa)
                ->url(fn() => route('khs.cetak', [
                    'semester' => $this->selectedSemester,
                    'tahunAkademikId' => $this->selectedTahunAkademikId
                ]))
                ->openUrlInNewTab(),
        ];
    }

    public function getTableQuery(): Builder
    {
        // Default query kosong jika belum ada semester yang dipilih
        if (!$this->selectedSemester || !$this->mahasiswa) {
            return KRSDetail::query()->where('id', 0);
        }

        return KRSDetail::query()
            ->join('krs', 'krs_detail.krs_id', '=', 'krs.id')
            ->where('krs.mahasiswa_id', $this->mahasiswa->id)
            ->where('krs.semester', $this->selectedSemester)
            ->where('krs_detail.status', 'aktif')
            ->whereHas('nilai')
            ->with(['mataKuliah', 'nilai'])
            ->select('krs_detail.*');
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
                TextColumn::make('nilai.nilai_tugas')
                    ->label('Nilai Tugas')
                    ->formatStateUsing(fn($state) => $state ? number_format($state, 2) : '-'),
                TextColumn::make('nilai.nilai_uts')
                    ->label('Nilai UTS')
                    ->formatStateUsing(fn($state) => $state ? number_format($state, 2) : '-'),
                TextColumn::make('nilai.nilai_uas')
                    ->label('Nilai UAS')
                    ->formatStateUsing(fn($state) => $state ? number_format($state, 2) : '-'),
                TextColumn::make('nilai.nilai_kehadiran')
                    ->label('Nilai Kehadiran')
                    ->formatStateUsing(fn($state) => $state ? number_format($state, 2) : '-'),
                TextColumn::make('nilai.nilai_akhir')
                    ->label('Nilai Akhir')
                    ->formatStateUsing(fn($state) => $state ? number_format($state, 2) : '-'),
                TextColumn::make('nilai.grade')
                    ->label('Grade')
                    ->formatStateUsing(fn($state) => $state ?: '-'),
            ])
            ->emptyStateHeading('Belum ada data nilai')
            ->emptyStateDescription('Belum ada data nilai untuk semester yang dipilih')
            ->paginated(false);
    }

    private function calculateIPK(): void
    {
        if (!$this->mahasiswa || !$this->selectedSemester) {
            $this->ipSemester = 0;
            $this->totalSKS = 0;
            $this->totalNilai = 0;
            return;
        }

        $krsDetails = KRSDetail::whereHas('krs', function ($query) {
            $query->where('mahasiswa_id', $this->mahasiswa->id)
                ->where('semester', $this->selectedSemester);
        })
            ->where('status', 'aktif')
            ->whereHas('nilai')
            ->with(['nilai', 'mataKuliah'])
            ->get();

        $totalSKS = 0;
        $totalBobot = 0;

        foreach ($krsDetails as $detail) {
            $sks = $detail->sks;
            $grade = $detail->nilai->grade ?? '';
            $bobot = 0;

            switch ($grade) {
                case 'A':
                    $bobot = 4.0;
                    break;
                case 'A-':
                    $bobot = 3.7;
                    break;
                case 'B+':
                    $bobot = 3.3;
                    break;
                case 'B':
                    $bobot = 3.0;
                    break;
                case 'B-':
                    $bobot = 2.7;
                    break;
                case 'C+':
                    $bobot = 2.3;
                    break;
                case 'C':
                    $bobot = 2.0;
                    break;
                case 'D':
                    $bobot = 1.0;
                    break;
                case 'E':
                    $bobot = 0;
                    break;
                default:
                    $bobot = 0;
            }

            $totalSKS += $sks;
            $totalBobot += ($sks * $bobot);
        }

        $this->totalSKS = $totalSKS;
        $this->totalNilai = $totalBobot;
        $this->ipSemester = $totalSKS > 0 ? round($totalBobot / $totalSKS, 2) : 0;
    }
}
