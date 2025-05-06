<?php

namespace App\Livewire;

use App\Models\Mahasiswa;
use App\Models\ProgramStudi;
use App\Models\StatusMahasiswa;
use App\Models\TahunAkademik;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class GenerateStatusMahasiswa extends Component implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];
    public $isGenerating = false;
    public $generateCount = 0;

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make()
                    ->schema([
                        Select::make('tahun_akademik_id')
                            ->label('Tahun Akademik')
                            ->options(TahunAkademik::all()->pluck('nama', 'id'))
                            ->required()
                            ->placeholder('Pilih Tahun Akademik')
                            ->default(function () {
                                $tahunAkademikAktif = TahunAkademik::getAktif();
                                return $tahunAkademikAktif ? $tahunAkademikAktif->id : null;
                            }),

                        Select::make('program_studi_id')
                            ->label('Program Studi')
                            ->options(ProgramStudi::all()->pluck('nama', 'id'))
                            ->placeholder('Semua Program Studi'),

                        Select::make('angkatan')
                            ->label('Angkatan')
                            ->options($this->getAvailableAngkatan())
                            ->placeholder('Semua Angkatan'),
                    ]),
            ])
            ->statePath('data');
    }

    private function getAvailableAngkatan(): array
    {
        $tahunMasuk = Mahasiswa::select('tahun_masuk')
            ->distinct()
            ->orderBy('tahun_masuk', 'desc')
            ->pluck('tahun_masuk')
            ->toArray();

        return array_combine($tahunMasuk, $tahunMasuk);
    }

    public function generate(): void
    {
        $this->isGenerating = true;
        $this->generateCount = 0;

        $tahunAkademikId = $this->data['tahun_akademik_id'];
        $programStudiId = $this->data['program_studi_id'] ?? null;
        $angkatan = $this->data['angkatan'] ?? null;

        $tahunAkademik = TahunAkademik::findOrFail($tahunAkademikId);

        // Query mahasiswa berdasarkan filter
        $mahasiswaQuery = Mahasiswa::query();

        if ($programStudiId) {
            $mahasiswaQuery->where('program_studi_id', $programStudiId);
        }

        if ($angkatan) {
            $mahasiswaQuery->where('tahun_masuk', $angkatan);
        }

        $mahasiswas = $mahasiswaQuery->get();

        foreach ($mahasiswas as $mahasiswa) {
            // Periksa apakah data status sudah ada
            $statusExists = StatusMahasiswa::where('mahasiswa_id', $mahasiswa->id)
                ->where('tahun_akademik_id', $tahunAkademikId)
                ->exists();

            if (!$statusExists) {
                // Hitung semester berdasarkan tahun masuk dan tahun akademik
                $semester = StatusMahasiswa::hitungSemester($mahasiswa, $tahunAkademik);

                StatusMahasiswa::create([
                    'mahasiswa_id' => $mahasiswa->id,
                    'tahun_akademik_id' => $tahunAkademikId,
                    'status' => 'tidak_aktif',
                    'semester' => $semester,
                    'sks_semester' => 0,
                    'sks_total' => ($semester - 1) * 20, // Perkiraan SKS yang telah diambil
                    'keterangan' => 'Status mahasiswa belum dikonfirmasi',
                ]);

                $this->generateCount++;
            }
        }

        $this->isGenerating = false;

        Notification::make()
            ->title('Generate Status Mahasiswa')
            ->body("Berhasil membuat {$this->generateCount} data status mahasiswa baru.")
            ->success()
            ->send();
    }

    public function render(): View
    {
        return view('livewire.generate-status-mahasiswa');
    }
}
