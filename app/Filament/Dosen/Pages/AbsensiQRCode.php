<?php

namespace App\Filament\Dosen\Pages;

use App\Models\AbsensiDosen;
use App\Models\Jadwal;
use App\Models\TahunAkademik;
use App\Models\Dosen;
use Carbon\Carbon;
use Filament\Forms\Components\Section;
use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\HtmlString;
use Filament\Pages\Concerns\InteractsWithFormActions;
use Filament\Support\Exceptions\Cancel;
use Filament\Support\Exceptions\Halt;

class AbsensiQRCode extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-qr-code';

    protected static string $view = 'filament.dosen.pages.absensi-qr-code';

    protected static ?string $navigationLabel = 'Absensi QR Code';

    protected static ?string $title = 'Absensi QR Code';

    protected static ?int $navigationSort = 15;

    public ?Jadwal $nextJadwal = null;

    public ?Jadwal $currentJadwal = null;

    public ?string $qrCodeData = null;

    public ?string $countdownTime = null;

    public ?string $statusMessage = null;

    public ?string $jenisTampilan = 'sekarang'; // sekarang, berikutnya, atau jadwal_akan_datang

    public function mount(): void
    {
        $this->fetchJadwalInfo();
        $this->generateQRCode();
    }

    public function fetchJadwalInfo(): void
    {
        // Dapatkan dosen yang sedang login
        $dosen = Dosen::where('user_id', Auth::id())->first();

        if (!$dosen) {
            $this->statusMessage = 'Data dosen tidak ditemukan.';
            return;
        }

        // Dapatkan tahun akademik aktif
        $tahunAkademikAktif = TahunAkademik::getAktif();

        if (!$tahunAkademikAktif) {
            $this->statusMessage = 'Tidak ada tahun akademik aktif saat ini.';
            return;
        }

        // Dapatkan hari dan jam saat ini
        $today = Carbon::now();
        $currentDay = $this->translateDayToIndonesian($today->format('l'));
        $currentTime = $today->format('H:i:s');

        // Cari jadwal yang sedang atau akan berlangsung hari ini
        $jadwalsHariIni = Jadwal::where('dosen_id', $dosen->id)
            ->where('tahun_akademik_id', $tahunAkademikAktif->id)
            ->where('hari', $currentDay)
            ->where('is_active', true)
            ->orderBy('jam_mulai')
            ->get();

        // Cek jadwal yang sedang berlangsung
        foreach ($jadwalsHariIni as $jadwal) {
            $mulai = Carbon::parse($jadwal->jam_mulai->format('H:i:s'));
            $selesai = Carbon::parse($jadwal->jam_selesai->format('H:i:s'));

            if ($currentTime >= $mulai->format('H:i:s') && $currentTime <= $selesai->format('H:i:s')) {
                $this->currentJadwal = $jadwal;
                $this->statusMessage = "Sedang berlangsung: {$jadwal->mataKuliah->nama} ({$mulai->format('H:i')} - {$selesai->format('H:i')})";
                $this->jenisTampilan = 'sekarang';
                break;
            }
        }

        // Jika tidak ada yang sedang berlangsung, cari jadwal berikutnya hari ini
        if (!$this->currentJadwal && $jadwalsHariIni->isNotEmpty()) {
            foreach ($jadwalsHariIni as $jadwal) {
                $mulai = Carbon::parse($jadwal->jam_mulai->format('H:i:s'));

                if ($currentTime < $mulai->format('H:i:s')) {
                    $this->nextJadwal = $jadwal;
                    $diffInMinutes = $today->diffInMinutes($mulai);
                    $hours = floor($diffInMinutes / 60);
                    $minutes = $diffInMinutes % 60;

                    $timeText = '';
                    if ($hours > 0) {
                        $timeText .= "{$hours} jam ";
                    }
                    $timeText .= "{$minutes} menit";

                    $this->countdownTime = $timeText;
                    $this->statusMessage = "Jadwal berikutnya: {$jadwal->mataKuliah->nama} ({$mulai->format('H:i')} - {$jadwal->jam_selesai->format('H:i')}), dalam {$timeText}";
                    $this->jenisTampilan = 'berikutnya';
                    break;
                }
            }

            if (!$this->nextJadwal) {
                $this->statusMessage = "Semua jadwal hari ini telah selesai.";
                // Cari jadwal untuk hari-hari berikutnya
                $this->cariJadwalAkanDatang($dosen, $tahunAkademikAktif);
            }
        } else if (!$this->currentJadwal) {
            $this->statusMessage = "Tidak ada jadwal mengajar hari ini.";
            // Cari jadwal untuk hari-hari berikutnya
            $this->cariJadwalAkanDatang($dosen, $tahunAkademikAktif);
        }
    }

    private function cariJadwalAkanDatang(Dosen $dosen, TahunAkademik $tahunAkademikAktif): void
    {
        $today = Carbon::now();
        $currentDayIndex = $today->dayOfWeek; // 0 (Minggu) sampai 6 (Sabtu)

        // Konversi ke format hari Indonesia (1 = Senin, 7 = Minggu)
        $currentDayIndex = ($currentDayIndex === 0) ? 7 : $currentDayIndex;

        // Daftar hari dalam bahasa Indonesia
        $daftarHari = [
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => 'Jumat',
            6 => 'Sabtu',
            7 => 'Minggu',
        ];

        // Cari jadwal untuk hari-hari berikutnya (Senin sampai Sabtu, jadi cek 6 hari ke depan)
        for ($i = 1; $i <= 7; $i++) {
            // Hitung indeks hari berikutnya (1-7)
            $nextDayIndex = ($currentDayIndex + $i) % 7;
            if ($nextDayIndex === 0) $nextDayIndex = 7; // Jika 0, ubah ke 7 (Minggu)

            // Lewati hari Minggu (di banyak kampus tidak ada kuliah di hari Minggu)
            if ($nextDayIndex === 7) continue;

            $nextDay = $daftarHari[$nextDayIndex];

            // Cari jadwal untuk hari tersebut
            $jadwal = Jadwal::where('dosen_id', $dosen->id)
                ->where('tahun_akademik_id', $tahunAkademikAktif->id)
                ->where('hari', $nextDay)
                ->where('is_active', true)
                ->orderBy('jam_mulai')
                ->first();

            if ($jadwal) {
                $this->nextJadwal = $jadwal;

                // Hitung berapa hari lagi
                $daysUntil = $i;
                $countdownHari = ($daysUntil === 1) ? "besok" : "{$daysUntil} hari lagi";

                $this->countdownTime = $countdownHari;
                $this->statusMessage = "Jadwal berikutnya: {$jadwal->mataKuliah->nama} pada hari {$nextDay} ({$jadwal->jam_mulai->format('H:i')} - {$jadwal->jam_selesai->format('H:i')}), {$countdownHari}";
                $this->jenisTampilan = 'jadwal_akan_datang';
                break;
            }
        }

        if (!$this->nextJadwal) {
            $this->statusMessage = "Tidak ada jadwal yang akan datang dalam 7 hari ke depan.";
        }
    }

    private function translateDayToIndonesian(string $day): string
    {
        $days = [
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
            'Sunday' => 'Minggu',
        ];

        return $days[$day] ?? $day;
    }

    public function generateQRCode(): void
    {
        $jadwal = $this->currentJadwal ?? $this->nextJadwal;

        if ($jadwal) {
            // Generate QR code data
            $this->qrCodeData = AbsensiDosen::generateQRToken($jadwal);
        }
    }

    public function refreshQRCode(): void
    {
        $this->fetchJadwalInfo();
        $this->generateQRCode();
    }

    public function getHeaderWidgetsColumns(): int|array
    {
        return 1;
    }
}
