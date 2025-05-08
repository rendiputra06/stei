<?php

namespace Database\Seeders;

use App\Models\Jadwal;
use App\Models\Presensi;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class PresensiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil semua jadwal aktif
        $jadwals = Jadwal::where('is_active', true)->get();

        // Buat presensi untuk setiap jadwal
        $jadwals->each(function ($jadwal) {
            // Buat presensi untuk 14 pertemuan
            for ($pertemuan = 1; $pertemuan <= 14; $pertemuan++) {
                // Hitung tanggal berdasarkan pertemuan
                $tanggal = $this->hitungTanggalPertemuan($jadwal, $pertemuan);

                // Buat presensi
                Presensi::create([
                    'jadwal_id' => $jadwal->id,
                    'pertemuan_ke' => $pertemuan,
                    'tanggal' => $tanggal,
                    'keterangan' => 'Pertemuan ' . $pertemuan,
                ]);
            }
        });
    }

    /**
     * Hitung tanggal pertemuan berdasarkan jadwal dan nomor pertemuan
     */
    private function hitungTanggalPertemuan(Jadwal $jadwal, int $pertemuan): Carbon
    {
        // Konversi hari ke angka (0: Minggu, 1: Senin, dst)
        $hariAngka = $this->konversiHariKeAngka($jadwal->hari);

        // Ambil tanggal awal semester dari tahun akademik
        $tanggalAwal = Carbon::parse($jadwal->tahunAkademik->tanggal_mulai);

        // Sesuaikan tanggal awal ke hari jadwal
        while ($tanggalAwal->dayOfWeek !== $hariAngka) {
            $tanggalAwal->addDay();
        }

        // Hitung tanggal pertemuan
        $tanggalPertemuan = $tanggalAwal->copy()->addWeeks($pertemuan - 1);

        return $tanggalPertemuan;
    }

    /**
     * Konversi hari ke angka
     */
    private function konversiHariKeAngka(string $hari): int
    {
        $konversi = [
            'Minggu' => 0,
            'Senin' => 1,
            'Selasa' => 2,
            'Rabu' => 3,
            'Kamis' => 4,
            'Jumat' => 5,
            'Sabtu' => 6,
        ];

        return $konversi[$hari] ?? 1; // Default ke Senin jika tidak ditemukan
    }
}
