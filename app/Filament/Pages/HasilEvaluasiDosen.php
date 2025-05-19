<?php

namespace App\Filament\Pages;

use App\Models\EdomJadwal;
use App\Models\EdomPengisian;
use App\Models\EdomPertanyaan;
use App\Models\EdomPengisianDetail;
use Filament\Pages\Page;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Form;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\HtmlString;

class HasilEvaluasiDosen extends Page implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-document-chart-bar';

    protected static ?string $navigationGroup = 'Evaluasi Dosen';

    protected static ?int $navigationSort = 4;

    protected static ?string $title = 'Hasil Evaluasi Dosen';

    protected static string $view = 'filament.pages.hasil-evaluasi-dosen';

    // Properties
    public $selectedJadwalId = null;
    public $selectedDosenId = null;
    public $selectedMataKuliahId = null;
    public $periodeLaporan = null;
    public $totalResponden = 0;
    public $hasilEvaluasi = [];
    public $komentar = [];
    public $rataRataTotal = 0;

    // Form data
    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Filter Laporan Evaluasi')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('jadwal_id')
                                    ->label('Periode Evaluasi')
                                    ->options(EdomJadwal::orderByDesc('created_at')->pluck('nama_periode', 'id'))
                                    ->searchable()
                                    ->reactive()
                                    ->afterStateUpdated(function ($state) {
                                        $this->selectedJadwalId = $state;
                                        $this->resetFilters();

                                        if ($state) {
                                            // Ambil periode laporan
                                            $this->periodeLaporan = EdomJadwal::find($state);
                                            $this->loadHasilEvaluasi();
                                        }
                                    }),

                                Select::make('dosen_id')
                                    ->label('Dosen')
                                    ->options(function () {
                                        if (!$this->selectedJadwalId) {
                                            return [];
                                        }

                                        return EdomPengisian::where('jadwal_id', $this->selectedJadwalId)
                                            ->distinct()
                                            ->join('dosen', 'edom_pengisian.dosen_id', '=', 'dosen.id')
                                            ->pluck('dosen.nama', 'dosen.id');
                                    })
                                    ->searchable()
                                    ->reactive()
                                    ->afterStateUpdated(function ($state) {
                                        $this->selectedDosenId = $state;
                                        $this->loadHasilEvaluasi();
                                    }),

                                Select::make('mata_kuliah_id')
                                    ->label('Mata Kuliah')
                                    ->options(function () {
                                        if (!$this->selectedJadwalId) {
                                            return [];
                                        }

                                        $query = EdomPengisian::where('jadwal_id', $this->selectedJadwalId);

                                        if ($this->selectedDosenId) {
                                            $query->where('dosen_id', $this->selectedDosenId);
                                        }

                                        return $query->distinct()
                                            ->join('mata_kuliah', 'edom_pengisian.mata_kuliah_id', '=', 'mata_kuliah.id')
                                            ->pluck('mata_kuliah.nama', 'mata_kuliah.id');
                                    })
                                    ->searchable()
                                    ->reactive()
                                    ->afterStateUpdated(function ($state) {
                                        $this->selectedMataKuliahId = $state;
                                        $this->loadHasilEvaluasi();
                                    }),
                            ])
                    ])
            ])
            ->statePath('data');
    }

    public function loadHasilEvaluasi(): void
    {
        if (!$this->selectedJadwalId) {
            $this->hasilEvaluasi = [];
            $this->komentar = [];
            $this->totalResponden = 0;
            $this->rataRataTotal = 0;
            return;
        }

        $query = EdomPengisian::where('jadwal_id', $this->selectedJadwalId)
            ->where('status', 'submitted');

        if ($this->selectedDosenId) {
            $query->where('dosen_id', $this->selectedDosenId);
        }

        if ($this->selectedMataKuliahId) {
            $query->where('mata_kuliah_id', $this->selectedMataKuliahId);
        }

        // Hitung total responden
        $this->totalResponden = $query->count();

        // Ambil semua pertanyaan
        $pertanyaan = EdomPertanyaan::all();

        // Reset hasil evaluasi
        $this->hasilEvaluasi = [];

        // Hitung rata-rata nilai untuk setiap pertanyaan
        foreach ($pertanyaan as $p) {
            $pengisianIds = $query->pluck('id')->toArray();
            $rataRata = EdomPengisianDetail::whereIn('pengisian_id', $pengisianIds)
                ->where('pertanyaan_id', $p->id)
                ->avg('nilai');

            $this->hasilEvaluasi[] = [
                'kode' => $p->kode,
                'pertanyaan' => $p->pertanyaan,
                'rata_rata' => $rataRata ? round($rataRata, 2) : 0,
            ];
        }

        // Hitung rata-rata total
        if (count($this->hasilEvaluasi) > 0) {
            $total = 0;
            foreach ($this->hasilEvaluasi as $hasil) {
                $total += $hasil['rata_rata'];
            }
            $this->rataRataTotal = round($total / count($this->hasilEvaluasi), 2);
        } else {
            $this->rataRataTotal = 0;
        }

        // Ambil komentar
        // $pengisianIds = $query->pluck('id');
        // $this->komentar = EdomPengisian::whereIn('id', $pengisianIds)
        //     ->where('komentar', '!=', '')
        //     ->whereNotNull('komentar')
        //     ->select('komentar', 'created_at')
        //     ->get()
        //     ->toArray();
    }

    public function resetFilters(): void
    {
        $this->selectedDosenId = null;
        $this->selectedMataKuliahId = null;
        $this->data['dosen_id'] = null;
        $this->data['mata_kuliah_id'] = null;
    }

    public function getTableQuery(): Builder
    {
        return EdomPengisian::query()
            ->when($this->selectedJadwalId, fn($query) => $query->where('jadwal_id', $this->selectedJadwalId))
            ->when($this->selectedDosenId, fn($query) => $query->where('dosen_id', $this->selectedDosenId))
            ->when($this->selectedMataKuliahId, fn($query) => $query->where('mata_kuliah_id', $this->selectedMataKuliahId))
            ->where('status', 'submitted');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())
            ->columns([
                TextColumn::make('jadwal.tahunAkademik.nama')
                    ->label('Tahun Akademik')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('jadwal.nama_periode')
                    ->label('Periode Evaluasi')
                    ->searchable(),
                TextColumn::make('mahasiswa.nama')
                    ->label('Mahasiswa')
                    ->searchable(),
                TextColumn::make('mataKuliah.nama')
                    ->label('Mata Kuliah')
                    ->searchable(),
                TextColumn::make('dosen.nama')
                    ->label('Dosen')
                    ->searchable(),
                TextColumn::make('tanggal_pengisian')
                    ->label('Tanggal Pengisian')
                    ->date()
                    ->sortable(),
                TextColumn::make('average_score')
                    ->label('Rata-rata Nilai')
                    ->getStateUsing(function ($record) {
                        $avg = $record->detail()->avg('nilai');
                        return number_format($avg, 2);
                    }),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'draft' => 'Draft',
                        'submitted' => 'Sudah Disubmit',
                        default => $state,
                    })
                    ->colors([
                        'warning' => 'draft',
                        'success' => 'submitted',
                    ]),
            ])
            ->filters([
                SelectFilter::make('jadwal_id')
                    ->label('Periode Evaluasi')
                    ->relationship('jadwal', 'nama_periode')
                    ->searchable()
                    ->preload(),
                Filter::make('tanggal_pengisian')
                    ->form([
                        \Filament\Forms\Components\DatePicker::make('tanggal_dari')
                            ->label('Dari Tanggal'),
                        \Filament\Forms\Components\DatePicker::make('tanggal_sampai')
                            ->label('Sampai Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['tanggal_dari'],
                                fn(Builder $query, $date): Builder => $query->whereDate('tanggal_pengisian', '>=', $date),
                            )
                            ->when(
                                $data['tanggal_sampai'],
                                fn(Builder $query, $date): Builder => $query->whereDate('tanggal_pengisian', '<=', $date),
                            );
                    }),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
