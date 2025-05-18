<?php

namespace App\Filament\Mahasiswa\Pages;

use App\Models\Dosen;
use App\Models\EdomJadwal;
use App\Models\EdomPengisian;
use App\Models\EdomPengisianDetail;
use App\Models\EdomPertanyaan;
use App\Models\Jadwal;
use App\Models\Mahasiswa;
use App\Models\MataKuliah;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Components\Placeholder;
use Filament\Pages\Page;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Filament\Actions\Action;

class IsiEvaluasiPage extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static bool $shouldRegisterNavigation = false;

    protected static string $view = 'filament.mahasiswa.pages.isi-evaluasi-page';

    public ?array $data = [];
    public $is_submitted = false;

    public $jadwal_id;
    public $jadwal_kuliah_id;
    public $dosen_id;
    public $mata_kuliah_id;

    public $jadwalEdom = null;
    public $jadwalKuliah = null;
    public $dosen = null;
    public $mataKuliah = null;

    public function mount($jadwal_id = null, $jadwal_kuliah_id = null, $dosen_id = null, $mata_kuliah_id = null): void
    {
        // Konversi parameter ke integer jika ada
        $this->jadwal_id = is_numeric($jadwal_id) ? (int) $jadwal_id : null;
        $this->jadwal_kuliah_id = is_numeric($jadwal_kuliah_id) ? (int) $jadwal_kuliah_id : null;
        $this->dosen_id = is_numeric($dosen_id) ? (int) $dosen_id : null;
        $this->mata_kuliah_id = is_numeric($mata_kuliah_id) ? (int) $mata_kuliah_id : null;

        // Cek parameter dari session jika tidak ada di URL
        if (!$this->jadwal_id || !$this->jadwal_kuliah_id || !$this->dosen_id || !$this->mata_kuliah_id) {
            // Coba ambil parameter dari session
            $sessionParams = session('edom_params', []);
            logger()->debug('Checking session params', ['session_params' => $sessionParams]);

            // Update parameter yang kosong
            if (!$this->jadwal_id && isset($sessionParams['jadwal_id'])) {
                $this->jadwal_id = (int) $sessionParams['jadwal_id'];
            }

            if (!$this->jadwal_kuliah_id && isset($sessionParams['jadwal_kuliah_id'])) {
                $this->jadwal_kuliah_id = (int) $sessionParams['jadwal_kuliah_id'];
            }

            if (!$this->dosen_id && isset($sessionParams['dosen_id'])) {
                $this->dosen_id = (int) $sessionParams['dosen_id'];
            }

            if (!$this->mata_kuliah_id && isset($sessionParams['mata_kuliah_id'])) {
                $this->mata_kuliah_id = (int) $sessionParams['mata_kuliah_id'];
            }
        }

        // Ambil parameter dari GET request secara eksplisit
        $this->jadwal_id = request()->query('jadwal_id');
        $this->jadwal_kuliah_id = request()->query('jadwal_kuliah_id');
        $this->dosen_id = request()->query('dosen_id');
        $this->mata_kuliah_id = request()->query('mata_kuliah_id');

        // Log parameter yang diterima
        logger()->debug('IsiEvaluasiPage mount parameters', [
            'request_params' => request()->all(),
            'converted_params' => [
                'jadwal_id' => $this->jadwal_id,
                'jadwal_kuliah_id' => $this->jadwal_kuliah_id,
                'dosen_id' => $this->dosen_id,
                'mata_kuliah_id' => $this->mata_kuliah_id
            ],
            'from_session' => session()->has('edom_params')
        ]);

        if (!$this->jadwal_id || !$this->jadwal_kuliah_id || !$this->dosen_id || !$this->mata_kuliah_id) {
            Notification::make()
                ->title('Gagal')
                ->body('Terjadi kesalahan. Parameter tidak lengkap.')
                ->danger()
                ->persistent()
                ->send();

            $this->redirect(route('filament.mahasiswa.pages.pengisian-edom-page'));
            return;
        }

        // Mengambil data yang diperlukan
        $this->jadwalEdom = EdomJadwal::find($this->jadwal_id);
        $this->jadwalKuliah = Jadwal::find($this->jadwal_kuliah_id);
        $this->dosen = Dosen::find($this->dosen_id);
        $this->mataKuliah = MataKuliah::find($this->mata_kuliah_id);

        // Log data yang ditemukan
        logger()->debug('IsiEvaluasiPage loaded data', [
            'jadwalEdom' => $this->jadwalEdom ? $this->jadwalEdom->id : null,
            'jadwalKuliah' => $this->jadwalKuliah ? $this->jadwalKuliah->id : null,
            'dosen' => $this->dosen ? $this->dosen->id : null,
            'mataKuliah' => $this->mataKuliah ? $this->mataKuliah->id : null,
        ]);

        if (!$this->jadwalEdom || !$this->jadwalKuliah || !$this->dosen || !$this->mataKuliah) {
            Notification::make()
                ->title('Gagal')
                ->body('Terjadi kesalahan. Data tidak ditemukan.')
                ->danger()
                ->persistent()
                ->send();

            $this->redirect(route('filament.mahasiswa.pages.pengisian-edom-page'));
            return;
        }

        // Validasi jadwal aktif
        if (
            !$this->jadwalEdom->is_aktif ||
            $this->jadwalEdom->tanggal_mulai->isAfter(now()) ||
            $this->jadwalEdom->tanggal_selesai->isBefore(now())
        ) {
            Notification::make()
                ->title('Periode Evaluasi Tidak Aktif')
                ->body('Periode evaluasi tidak dalam masa aktif.')
                ->danger()
                ->persistent()
                ->send();

            $this->redirect(route('filament.mahasiswa.pages.pengisian-edom-page'));
            return;
        }

        // Hapus parameter dari session setelah digunakan
        session()->forget('edom_params');

        // Ambil data mahasiswa yang login
        $mahasiswaId = Mahasiswa::where('user_id', Auth::id())->first()?->id;

        if (!$mahasiswaId) {
            Notification::make()
                ->title('Gagal')
                ->body('Data mahasiswa tidak ditemukan.')
                ->danger()
                ->persistent()
                ->send();

            $this->redirect(route('filament.mahasiswa.pages.pengisian-edom-page'));
            return;
        }

        // Cek apakah mahasiswa memiliki KRS untuk jadwal kuliah ini
        $hasKrs = $this->jadwalKuliah->krsDetail()
            ->whereHas('krs', function ($query) use ($mahasiswaId) {
                $query->where('mahasiswa_id', $mahasiswaId)
                    ->where('status', 'approved');
            })
            ->exists();

        if (!$hasKrs) {
            Notification::make()
                ->title('Akses Ditolak')
                ->body('Anda tidak terdaftar dalam mata kuliah ini.')
                ->danger()
                ->persistent()
                ->send();

            $this->redirect(route('filament.mahasiswa.pages.pengisian-edom-page'));
            return;
        }

        // Cek apakah sudah ada pengisian sebelumnya
        $pengisian = EdomPengisian::where('jadwal_id', $this->jadwal_id)
            ->where('mahasiswa_id', $mahasiswaId)
            ->where('jadwal_kuliah_id', $this->jadwal_kuliah_id)
            ->where('dosen_id', $this->dosen_id)
            ->with('detail.pertanyaan')
            ->first();

        // Persiapkan data jawaban jika ada
        if ($pengisian) {
            $this->is_submitted = $pengisian->status === 'submitted';

            $jawaban = [];
            foreach ($pengisian->detail as $detail) {
                $pertanyaanId = $detail->pertanyaan_id;
                if ($detail->pertanyaan->jenis === 'likert_scale') {
                    $jawaban[$pertanyaanId]['nilai'] = $detail->nilai;
                } else {
                    $jawaban[$pertanyaanId]['jawaban_text'] = $detail->jawaban_text;
                }
            }
            $this->data['jawaban'] = $jawaban;
        }

        // Form
        $this->form->fill($this->data);
    }

    public function form(Form $form): Form
    {
        if (!$this->jadwalEdom) {
            return $form->schema([]);
        }

        // Ambil pertanyaan dari template
        $pertanyaan = $this->jadwalEdom->template->pertanyaan()->orderBy('urutan')->get();

        if ($pertanyaan->isEmpty()) {
            return $form->schema([
                Placeholder::make('info')
                    ->label('Informasi')
                    ->content('Belum ada pertanyaan evaluasi untuk periode ini.')
            ]);
        }

        $fields = [];

        // Informasi header
        $fields[] = Section::make('Informasi Evaluasi')
            ->schema([
                Placeholder::make('periode')
                    ->label('Periode Evaluasi')
                    ->content($this->jadwalEdom->nama_periode),
                Placeholder::make('dosen')
                    ->label('Dosen')
                    ->content($this->dosen->nama),
                Placeholder::make('mata_kuliah')
                    ->label('Mata Kuliah')
                    ->content($this->mataKuliah->nama_mata_kuliah),
                Placeholder::make('kelas')
                    ->label('Kelas')
                    ->content($this->jadwalKuliah->kelas),
            ])
            ->columns(2);

        // Petunjuk pengisian
        $fields[] = Section::make('Petunjuk Pengisian')
            ->schema([
                Placeholder::make('petunjuk')
                    ->content('Untuk pertanyaan skala: 1 = Sangat Tidak Setuju, 2 = Tidak Setuju, 3 = Netral, 4 = Setuju, 5 = Sangat Setuju')
            ]);

        // Buat fields berdasarkan pertanyaan
        $fields[] = Section::make('Formulir Evaluasi')
            ->schema(function () use ($pertanyaan) {
                $questionFields = [];

                foreach ($pertanyaan as $item) {
                    if ($item->jenis === 'likert_scale') {
                        $radio = Radio::make("jawaban.{$item->id}.nilai")
                            ->label($item->pertanyaan)
                            ->options([
                                1 => '1 - Sangat Tidak Setuju',
                                2 => '2 - Tidak Setuju',
                                3 => '3 - Netral',
                                4 => '4 - Setuju',
                                5 => '5 - Sangat Setuju',
                            ])
                            ->required($item->is_required && !$this->is_submitted)
                            ->columnSpanFull();

                        if ($this->is_submitted) {
                            $radio->disabled();
                        }

                        $questionFields[] = $radio;
                    } else {
                        $textarea = Textarea::make("jawaban.{$item->id}.jawaban_text")
                            ->label($item->pertanyaan)
                            ->required($item->is_required && !$this->is_submitted)
                            ->columnSpanFull();

                        if ($this->is_submitted) {
                            $textarea->disabled();
                        }

                        $questionFields[] = $textarea;
                    }
                }

                return $questionFields;
            });

        return $form
            ->schema($fields)
            ->statePath('data')
            ->disabled($this->is_submitted);
    }

    public function simpan(): void
    {
        // Validasi form
        $this->form->validate();

        // Ambil data mahasiswa yang login
        $mahasiswaId = Mahasiswa::where('user_id', Auth::id())->first()?->id;

        if (!$mahasiswaId) {
            Notification::make()
                ->title('Gagal')
                ->body('Terjadi kesalahan. Silakan coba lagi.')
                ->danger()
                ->send();
            return;
        }

        // Mulai transaksi database
        DB::beginTransaction();

        try {
            // Buat/update pengisian EDOM
            $pengisian = EdomPengisian::updateOrCreate(
                [
                    'jadwal_id' => $this->jadwal_id,
                    'mahasiswa_id' => $mahasiswaId,
                    'jadwal_kuliah_id' => $this->jadwal_kuliah_id,
                    'dosen_id' => $this->dosen_id,
                ],
                [
                    'mata_kuliah_id' => $this->mata_kuliah_id,
                    'tanggal_pengisian' => now()->format('Y-m-d'),
                    'status' => 'draft',
                ]
            );

            // Hapus jawaban sebelumnya
            EdomPengisianDetail::where('pengisian_id', $pengisian->id)->delete();

            // Simpan jawaban
            foreach ($this->data['jawaban'] ?? [] as $pertanyaanId => $data) {
                // Cek apakah pertanyaan ada
                $pertanyaan = EdomPertanyaan::find($pertanyaanId);
                if (!$pertanyaan) {
                    continue;
                }

                // Buat jawaban baru
                EdomPengisianDetail::create([
                    'pengisian_id' => $pengisian->id,
                    'pertanyaan_id' => $pertanyaanId,
                    'nilai' => $data['nilai'] ?? null,
                    'jawaban_text' => $data['jawaban_text'] ?? null,
                ]);
            }

            DB::commit();

            Notification::make()
                ->title('Tersimpan')
                ->body('Evaluasi berhasil disimpan sebagai draft.')
                ->success()
                ->send();

            // Redirect ke halaman pengisian menggunakan metode Livewire
            $this->redirect(route('filament.mahasiswa.pages.pengisian-edom-page'));
        } catch (\Exception $e) {
            DB::rollBack();

            Notification::make()
                ->title('Gagal')
                ->body('Terjadi kesalahan: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function submit(): void
    {
        // Validasi form
        $this->form->validate();

        // Ambil data mahasiswa yang login
        $mahasiswaId = Mahasiswa::where('user_id', Auth::id())->first()?->id;

        if (!$mahasiswaId) {
            Notification::make()
                ->title('Gagal')
                ->body('Terjadi kesalahan. Silakan coba lagi.')
                ->danger()
                ->send();
            return;
        }

        // Mulai transaksi database
        DB::beginTransaction();

        try {
            // Buat/update pengisian EDOM
            $pengisian = EdomPengisian::updateOrCreate(
                [
                    'jadwal_id' => $this->jadwal_id,
                    'mahasiswa_id' => $mahasiswaId,
                    'jadwal_kuliah_id' => $this->jadwal_kuliah_id,
                    'dosen_id' => $this->dosen_id,
                ],
                [
                    'mata_kuliah_id' => $this->mata_kuliah_id,
                    'tanggal_pengisian' => now()->format('Y-m-d'),
                    'status' => 'submitted', // Status submitted
                ]
            );

            // Hapus jawaban sebelumnya
            EdomPengisianDetail::where('pengisian_id', $pengisian->id)->delete();

            // Simpan jawaban
            foreach ($this->data['jawaban'] ?? [] as $pertanyaanId => $data) {
                // Cek apakah pertanyaan ada
                $pertanyaan = EdomPertanyaan::find($pertanyaanId);
                if (!$pertanyaan) {
                    continue;
                }

                // Buat jawaban baru
                EdomPengisianDetail::create([
                    'pengisian_id' => $pengisian->id,
                    'pertanyaan_id' => $pertanyaanId,
                    'nilai' => $data['nilai'] ?? null,
                    'jawaban_text' => $data['jawaban_text'] ?? null,
                ]);
            }

            DB::commit();

            Notification::make()
                ->title('Berhasil')
                ->body('Evaluasi berhasil dikumpulkan.')
                ->success()
                ->send();

            // Redirect ke halaman pengisian menggunakan metode Livewire
            $this->redirect(route('filament.mahasiswa.pages.pengisian-edom-page'));
        } catch (\Exception $e) {
            DB::rollBack();

            Notification::make()
                ->title('Gagal')
                ->body('Terjadi kesalahan: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }

    protected function getFormActions(): array
    {
        if ($this->is_submitted) {
            return [
                Action::make('kembali')
                    ->label('Kembali')
                    ->color('gray')
                    ->url(fn(): string => route('filament.mahasiswa.pages.edom-riwayat-page'))
            ];
        }

        return [
            Action::make('simpan')
                ->label('Simpan Draft')
                ->action('simpan')
                ->color('secondary'),

            Action::make('submit')
                ->label('Kumpulkan')
                ->action('submit')
                ->color('primary')
                ->requiresConfirmation()
                ->modalHeading('Kumpulkan Evaluasi')
                ->modalDescription('Setelah dikumpulkan, evaluasi tidak dapat diubah lagi. Yakin ingin melanjutkan?')
                ->modalSubmitActionLabel('Ya, Kumpulkan'),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('kembali')
                ->label('Kembali')
                ->color('gray')
                ->url(fn(): string => route('filament.mahasiswa.pages.pengisian-edom-page')),
        ];
    }
}
