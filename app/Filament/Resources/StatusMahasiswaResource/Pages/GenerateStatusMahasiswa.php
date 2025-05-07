<?php

namespace App\Filament\Resources\StatusMahasiswaResource\Pages;

use App\Filament\Resources\StatusMahasiswaResource;
use App\Models\TahunAkademik;
use Filament\Resources\Pages\Page;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;
use App\Models\Mahasiswa;
use App\Models\StatusMahasiswa;
use Livewire\Attributes\On;

class GenerateStatusMahasiswa extends Page
{
    protected static string $resource = StatusMahasiswaResource::class;

    protected static string $view = 'filament.resources.status-mahasiswa-resource.pages.generate-status-mahasiswa';

    protected static ?string $title = 'Generate Status Mahasiswa';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Generate Status Mahasiswa')
                    ->description('Generate status mahasiswa otomatis berdasarkan tahun akademik yang dipilih')
                    ->schema([
                        Forms\Components\Select::make('tahun_akademik_id')
                            ->label('Tahun Akademik')
                            ->options(TahunAkademik::pluck('nama', 'id'))
                            ->required()
                            ->searchable()
                            ->default(function () {
                                $tahunAktif = TahunAkademik::getAktif();
                                return $tahunAktif ? $tahunAktif->id : null;
                            }),
                        Forms\Components\Select::make('default_status')
                            ->label('Status Default')
                            ->options(StatusMahasiswa::getStatusList())
                            ->default('aktif')
                            ->required(),
                    ]),
            ])
            ->statePath('data');
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('kembali')
                ->label('Kembali')
                ->url(StatusMahasiswaResource::getUrl())
                ->color('gray')
                ->icon('heroicon-o-arrow-left'),
        ];
    }

    public function generate()
    {
        $data = $this->form->getState();

        $tahunAkademikId = $data['tahun_akademik_id'];
        $defaultStatus = $data['default_status'];

        $tahunAkademik = TahunAkademik::findOrFail($tahunAkademikId);

        // Mulai transaksi
        DB::beginTransaction();
        try {
            // Ambil semua mahasiswa aktif
            $mahasiswas = Mahasiswa::where('status', 'aktif')->get();
            $counter = 0;

            foreach ($mahasiswas as $mahasiswa) {
                // Periksa apakah status sudah ada untuk tahun akademik ini
                $statusExists = StatusMahasiswa::where('mahasiswa_id', $mahasiswa->id)
                    ->where('tahun_akademik_id', $tahunAkademikId)
                    ->exists();

                // Jika belum ada, buat status baru
                if (!$statusExists) {
                    // Hitung semester berdasarkan tahun masuk
                    $semester = StatusMahasiswa::hitungSemester($mahasiswa, $tahunAkademik);

                    // Cari status sebelumnya untuk mendapatkan IPK dan total SKS
                    $statusSebelumnya = StatusMahasiswa::where('mahasiswa_id', $mahasiswa->id)
                        ->orderBy('created_at', 'desc')
                        ->first();

                    StatusMahasiswa::create([
                        'mahasiswa_id' => $mahasiswa->id,
                        'tahun_akademik_id' => $tahunAkademikId,
                        'status' => $defaultStatus,
                        'semester' => $semester,
                        'ip_semester' => 0, // Default, akan diupdate nanti
                        'ipk' => $statusSebelumnya ? $statusSebelumnya->ipk : 0,
                        'sks_semester' => 0, // Default, akan diupdate nanti
                        'sks_total' => $statusSebelumnya ? $statusSebelumnya->sks_total : 0,
                        'keterangan' => 'Auto-generated status',
                    ]);

                    $counter++;
                }
            }

            DB::commit();

            Notification::make()
                ->title("Berhasil generate status untuk {$counter} mahasiswa")
                ->success()
                ->send();

            return redirect()->to(StatusMahasiswaResource::getUrl());
        } catch (\Exception $e) {
            DB::rollBack();

            Notification::make()
                ->title('Gagal generate status')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }

        return null;
    }
}
