<?php

namespace App\Filament\Pages;

use App\Models\AbsensiDosen;
use App\Models\Dosen;
use App\Models\Jadwal;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;

class ScanAbsensiDosen extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-qr-code';

    protected static string $view = 'filament.pages.scan-absensi-dosen';

    protected static ?string $navigationLabel = 'Scan QR Absensi Dosen';

    protected static ?string $title = 'Scan QR Absensi Dosen';

    protected static ?int $navigationSort = 15;

    public ?string $scanResult = null;

    public ?string $dosenName = null;

    public ?string $jadwalInfo = null;

    public ?string $statusMessage = null;

    public function processQRCode(string $qrData): void
    {
        $this->scanResult = null;
        $this->dosenName = null;
        $this->jadwalInfo = null;
        $this->statusMessage = null;

        try {
            // Validasi token QR
            if (!AbsensiDosen::validateQRToken($qrData)) {
                $this->statusMessage = 'QR Code tidak valid atau sudah kadaluarsa.';
                $this->dispatch('showResult', status: 'error');
                return;
            }

            // Dekode data QR
            $decoded = AbsensiDosen::decodeQRToken($qrData);
            $jadwalId = $decoded['jadwal_id'];
            $dosenId = $decoded['dosen_id'];

            // Cari data dosen dan jadwal
            $dosen = Dosen::find($dosenId);
            $jadwal = Jadwal::find($jadwalId);

            if (!$dosen || !$jadwal) {
                $this->statusMessage = 'Data dosen atau jadwal tidak ditemukan.';
                $this->dispatch('showResult', status: 'error');
                return;
            }

            $this->dosenName = $dosen->nama;
            $this->jadwalInfo = "{$jadwal->mataKuliah->nama} ({$jadwal->jam_mulai->format('H:i')} - {$jadwal->jam_selesai->format('H:i')})";

            // Cek apakah sudah absen masuk hari ini
            $today = now()->format('Y-m-d');
            $existingAbsensi = AbsensiDosen::where('dosen_id', $dosenId)
                ->where('jadwal_id', $jadwalId)
                ->whereDate('tanggal', $today)
                ->first();

            DB::beginTransaction();

            if (!$existingAbsensi) {
                // Absensi masuk
                AbsensiDosen::create([
                    'dosen_id' => $dosenId,
                    'jadwal_id' => $jadwalId,
                    'tanggal' => $today,
                    'jam_masuk' => now(),
                    'status' => 'hadir',
                ]);

                $this->statusMessage = 'Berhasil melakukan absensi masuk.';
                $this->dispatch('showResult', status: 'success-in');
            } else if (!$existingAbsensi->jam_keluar) {
                // Absensi keluar
                $existingAbsensi->update([
                    'jam_keluar' => now(),
                ]);

                $this->statusMessage = 'Berhasil melakukan absensi keluar.';
                $this->dispatch('showResult', status: 'success-out');
            } else {
                $this->statusMessage = 'Anda sudah melakukan absensi masuk dan keluar hari ini.';
                $this->dispatch('showResult', status: 'warning');
            }

            DB::commit();

            Notification::make()
                ->title($this->statusMessage)
                ->success()
                ->send();
        } catch (\Exception $e) {
            DB::rollBack();

            $this->statusMessage = 'Terjadi kesalahan: ' . $e->getMessage();
            $this->dispatch('showResult', status: 'error');

            Notification::make()
                ->title('Terjadi kesalahan')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }
}
