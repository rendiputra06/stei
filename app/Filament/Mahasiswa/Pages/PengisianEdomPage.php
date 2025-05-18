<?php

namespace App\Filament\Mahasiswa\Pages;

use App\Models\Dosen;
use App\Models\EdomJadwal;
use App\Models\EdomPengisian;
use App\Models\Mahasiswa;
use App\Models\Jadwal;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Session;

class PengisianEdomPage extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static ?string $navigationGroup = 'Evaluasi Dosen';
    protected static ?string $navigationLabel = 'Evaluasi';
    protected static ?int $navigationSort = 1;

    protected static string $view = 'filament.mahasiswa.pages.pengisian-edom-page';

    public $jadwalId = null;
    public $dosenList = [];
    public $jadwalAktif = null;

    public function mount(): void
    {
        // Mengambil jadwal EDOM yang aktif
        $this->jadwalAktif = EdomJadwal::where('is_aktif', true)
            ->whereDate('tanggal_mulai', '<=', now())
            ->whereDate('tanggal_selesai', '>=', now())
            ->latest()
            ->first();

        // Mengambil ID mahasiswa yang login
        $mahasiswaId = Mahasiswa::where('user_id', Auth::id())->first()?->id;

        if ($this->jadwalAktif && $mahasiswaId) {
            $this->loadDosenList($mahasiswaId);
        }
    }

    /**
     * Load daftar dosen yang dapat dievaluasi oleh mahasiswa
     */
    private function loadDosenList($mahasiswaId): void
    {
        // Ambil daftar jadwal kuliah mahasiswa pada semester aktif
        $jadwalKuliah = Jadwal::whereHas('krsDetail', function ($query) use ($mahasiswaId) {
            $query->whereHas('krs', function ($q) use ($mahasiswaId) {
                $q->where('mahasiswa_id', $mahasiswaId)
                    ->where('status', 'approved');
            });
        })
            ->where('tahun_akademik_id', $this->jadwalAktif->tahun_akademik_id)
            ->with(['dosen', 'mataKuliah'])
            ->get();

        // Filter dosen berdasarkan yang dipilih di jadwal EDOM
        $dosenIds = $this->jadwalAktif->dosen()->pluck('dosen.id')->toArray();
        $hasSelectedDosen = count($dosenIds) > 0;

        // Ambil daftar pengisian EDOM yang sudah ada
        $existingPengisian = EdomPengisian::where('jadwal_id', $this->jadwalAktif->id)
            ->where('mahasiswa_id', $mahasiswaId)
            ->pluck('jadwal_kuliah_id')
            ->toArray();

        $this->dosenList = $jadwalKuliah
            ->filter(function ($jadwal) use ($dosenIds, $hasSelectedDosen, $existingPengisian) {
                // Jika sudah diisi, jangan tampilkan
                if (in_array($jadwal->id, $existingPengisian)) {
                    return false;
                }

                // Jika admin memilih dosen tertentu, filter berdasarkan dosen tersebut
                if ($hasSelectedDosen) {
                    return in_array($jadwal->dosen_id, $dosenIds);
                }

                // Jika admin tidak memilih dosen tertentu, tampilkan semua
                return true;
            })
            ->map(function ($jadwal) {
                return [
                    'id' => $jadwal->id,
                    'dosen_id' => $jadwal->dosen_id,
                    'dosen_nama' => $jadwal->dosen->nama,
                    'mata_kuliah_id' => $jadwal->mata_kuliah_id,
                    'mata_kuliah' => $jadwal->mataKuliah->nama_mata_kuliah,
                    'kelas' => $jadwal->kelas,
                    'status' => 'Belum Diisi',
                ];
            })
            ->values()
            ->toArray();
    }

    /**
     * Pengisian evaluasi
     */
    public function isiEvaluasi($jadwalKuliahId): void
    {
        $mahasiswaId = Mahasiswa::where('user_id', Auth::id())->first()?->id;

        if (!$mahasiswaId || !$this->jadwalAktif) {
            Notification::make()
                ->title('Gagal')
                ->body('Terjadi kesalahan. Silakan coba lagi.')
                ->danger()
                ->send();
            return;
        }

        // Cari data jadwal dan dosen dari dosenList
        $jadwalData = collect($this->dosenList)->firstWhere('id', $jadwalKuliahId);

        if (!$jadwalData) {
            // Alternatif: Ambil data dari database jika tidak ada di dosenList
            $jadwalKuliah = Jadwal::with(['dosen', 'mataKuliah'])->find($jadwalKuliahId);

            if (!$jadwalKuliah) {
                Notification::make()
                    ->title('Gagal')
                    ->body('Jadwal kuliah tidak ditemukan.')
                    ->danger()
                    ->send();
                return;
            }

            $dosenId = $jadwalKuliah->dosen_id;
            $mataKuliahId = $jadwalKuliah->mata_kuliah_id;
        } else {
            // Gunakan data dari dosenList yang sudah ada
            $dosenId = $jadwalData['dosen_id'];
            $mataKuliahId = $jadwalData['mata_kuliah_id'];
        }

        // Log parameter yang akan dikirim
        logger()->debug('Redirecting to IsiEvaluasiPage with parameters', [
            'jadwal_id' => $this->jadwalAktif->id,
            'jadwal_kuliah_id' => $jadwalKuliahId,
            'dosen_id' => $dosenId,
            'mata_kuliah_id' => $mataKuliahId
        ]);

        // Cek apakah sudah ada pengisian sebelumnya
        $existingPengisian = EdomPengisian::where('jadwal_id', $this->jadwalAktif->id)
            ->where('mahasiswa_id', $mahasiswaId)
            ->where('jadwal_kuliah_id', $jadwalKuliahId)
            ->first();

        if ($existingPengisian) {
            Notification::make()
                ->title('Evaluasi Sudah Ada')
                ->body('Anda sudah mengisi evaluasi untuk jadwal kuliah ini.')
                ->warning()
                ->send();
            return;
        }

        // Siapkan parameter untuk navigasi
        $params = [
            'jadwal_id' => $this->jadwalAktif->id,
            'jadwal_kuliah_id' => $jadwalKuliahId,
            'dosen_id' => $dosenId,
            'mata_kuliah_id' => $mataKuliahId,
        ];

        // Simpan parameter ke session sebagai backup
        Session::flash('edom_params', $params);

        // Buat URL untuk redirect
        $url = route('filament.mahasiswa.pages.isi-evaluasi-page', $params);

        // Debug URL yang akan diakses
        logger()->debug('URL for redirect', ['url' => $url]);

        // Coba navigasi dengan Livewire redirect
        $this->redirect($url);
    }

    /**
     * Metode alternatif untuk navigasi ke halaman pengisian evaluasi
     * yang menggunakan JavaScript langsung
     */
    public function navigateToEvaluasi($jadwalKuliahId, $dosenId, $mataKuliahId): void
    {
        $params = [
            'jadwal_id' => $this->jadwalAktif->id,
            'jadwal_kuliah_id' => $jadwalKuliahId,
            'dosen_id' => $dosenId,
            'mata_kuliah_id' => $mataKuliahId,
        ];

        // Log parameter
        logger()->debug('Navigate via JavaScript', $params);

        // Simpan parameter ke session
        Session::flash('edom_params', $params);

        // URL destination
        $url = route('filament.mahasiswa.pages.isi-evaluasi-page', $params);

        // Return URL untuk JS menghandle navigasi
        $this->dispatch('navigateToUrl', ['url' => $url]);
    }

    public function refreshDosenList(): void
    {
        $mahasiswaId = Mahasiswa::where('user_id', Auth::id())->first()?->id;

        if ($this->jadwalAktif && $mahasiswaId) {
            $this->loadDosenList($mahasiswaId);
        }

        Notification::make()
            ->title('Daftar Diperbarui')
            ->success()
            ->send();
    }
}
