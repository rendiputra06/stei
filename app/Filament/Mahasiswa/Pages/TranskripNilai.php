<?php

namespace App\Filament\Mahasiswa\Pages;

use App\Models\KRS;
use App\Models\KRSDetail;
use App\Models\Mahasiswa;
use App\Models\TahunAkademik;
use Filament\Actions\Action;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TranskripNilai extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.mahasiswa.pages.transkrip-nilai';

    protected static ?string $title = 'Transkrip Nilai';

    protected static ?string $navigationLabel = 'Transkrip Nilai';

    protected static ?int $navigationSort = 4;

    // Properties
    public $mahasiswa = null;
    public $ipk = 0;
    public $totalSKS = 0;
    public $totalNilai = 0;
    public $semesterTertinggi = 0;

    public function mount(): void
    {
        // Ambil data mahasiswa berdasarkan user yang login
        $this->mahasiswa = Mahasiswa::where('user_id', Auth::id())->first();

        if (!$this->mahasiswa) {
            return;
        }

        // Hitung IPK dan data lainnya
        $this->calculateIPK();

        // Cari semester tertinggi
        $this->semesterTertinggi = KRS::where('mahasiswa_id', $this->mahasiswa->id)
            ->whereHas('krsDetail.nilai')
            ->max('semester') ?? 0;
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('print_transkrip')
                ->label('Cetak Transkrip')
                ->icon('heroicon-o-printer')
                ->color('success')
                ->visible(fn() => $this->mahasiswa)
                ->url(fn() => route('transkrip.cetak'))
                ->openUrlInNewTab(),
        ];
    }

    public function getTableQuery(): Builder
    {
        // Default query kosong jika belum ada mahasiswa
        if (!$this->mahasiswa) {
            return KRSDetail::query()->where('id', 0);
        }

        return KRSDetail::query()
            ->join('krs', 'krs_detail.krs_id', '=', 'krs.id')
            ->where('krs.mahasiswa_id', $this->mahasiswa->id)
            ->where('krs_detail.status', 'aktif')
            ->whereHas('nilai')
            ->with(['mataKuliah', 'nilai', 'krs'])
            ->select('krs_detail.*')
            ->orderBy('krs.semester');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())
            ->columns([
                TextColumn::make('krs.semester')
                    ->label('Semester')
                    ->sortable(),
                TextColumn::make('mataKuliah.kode')
                    ->label('Kode MK')
                    ->searchable(),
                TextColumn::make('mataKuliah.nama')
                    ->label('Mata Kuliah')
                    ->searchable(),
                TextColumn::make('sks')
                    ->label('SKS'),
                TextColumn::make('nilai.nilai_akhir')
                    ->label('Nilai Akhir')
                    ->formatStateUsing(fn($state) => $state ? number_format($state, 2) : '-'),
                TextColumn::make('nilai.grade')
                    ->label('Grade')
                    ->formatStateUsing(fn($state) => $state ?: '-'),
                TextColumn::make('bobot')
                    ->label('Bobot')
                    ->state(function (KRSDetail $record): float {
                        $grade = $record->nilai->grade ?? '';
                        return $this->getNilaiBobot($grade);
                    })
                    ->formatStateUsing(fn($state) => number_format($state, 1)),
                TextColumn::make('mutu')
                    ->label('Mutu')
                    ->state(function (KRSDetail $record): float {
                        $grade = $record->nilai->grade ?? '';
                        $bobot = $this->getNilaiBobot($grade);
                        return $bobot * $record->sks;
                    })
                    ->formatStateUsing(fn($state) => number_format($state, 1)),
            ])
            ->emptyStateHeading('Belum ada data nilai')
            ->emptyStateDescription('Belum ada data nilai yang bisa ditampilkan')
            ->paginated(false);
    }

    private function calculateIPK(): void
    {
        if (!$this->mahasiswa) {
            $this->ipk = 0;
            $this->totalSKS = 0;
            $this->totalNilai = 0;
            return;
        }

        $krsDetails = KRSDetail::whereHas('krs', function ($query) {
            $query->where('mahasiswa_id', $this->mahasiswa->id);
        })
            ->where('status', 'aktif')
            ->whereHas('nilai')
            ->with(['nilai'])
            ->get();

        $totalSKS = 0;
        $totalBobot = 0;

        foreach ($krsDetails as $detail) {
            $sks = $detail->sks;
            $grade = $detail->nilai->grade ?? '';
            $bobot = $this->getNilaiBobot($grade);

            $totalSKS += $sks;
            $totalBobot += ($bobot * $sks);
        }

        $this->totalSKS = $totalSKS;
        $this->totalNilai = $totalBobot;
        $this->ipk = $totalSKS > 0 ? round($totalBobot / $totalSKS, 2) : 0;
    }

    private function getNilaiBobot(string $grade): float
    {
        switch ($grade) {
            case 'A':
                return 4.0;
            case 'A-':
                return 3.7;
            case 'B+':
                return 3.3;
            case 'B':
                return 3.0;
            case 'B-':
                return 2.7;
            case 'C+':
                return 2.3;
            case 'C':
                return 2.0;
            case 'D':
                return 1.0;
            case 'E':
                return 0.0;
            default:
                return 0.0;
        }
    }
}
