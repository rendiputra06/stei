<?php

namespace Database\Seeders;

use App\Models\KRSDetail;
use App\Models\Presensi;
use App\Models\PresensiDetail;
use Illuminate\Database\Seeder;

class PresensiDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil semua presensi
        $allPresensi = Presensi::all();

        // Untuk setiap presensi
        $allPresensi->each(function ($presensi) {
            // Ambil jadwal terkait
            $jadwal = $presensi->jadwal;

            // Ambil semua KRS Detail yang mengambil jadwal ini
            $krsDetails = KRSDetail::where('jadwal_id', $jadwal->id)
                ->where('status', 'aktif')
                ->get();

            // Untuk setiap mahasiswa di KRS Detail
            $krsDetails->each(function ($krsDetail) use ($presensi) {
                // Status presensi (80% hadir, 20% acak)
                $status = $this->getRandomStatus();

                // Buat presensi detail
                PresensiDetail::create([
                    'presensi_id' => $presensi->id,
                    'mahasiswa_id' => $krsDetail->krs->mahasiswa_id,
                    'status' => $status,
                    'keterangan' => $status !== 'hadir' ? $this->getRandomKeterangan($status) : null,
                ]);
            });
        });
    }

    /**
     * Mendapatkan status presensi secara acak
     */
    private function getRandomStatus(): string
    {
        // Probabilitas status (80% hadir, 7% izin, 8% sakit, 5% alpa)
        $random = rand(1, 100);

        if ($random <= 80) {
            return 'hadir';
        } elseif ($random <= 87) {
            return 'izin';
        } elseif ($random <= 95) {
            return 'sakit';
        } else {
            return 'alpa';
        }
    }

    /**
     * Mendapatkan keterangan acak berdasarkan status
     */
    private function getRandomKeterangan(string $status): string
    {
        if ($status === 'izin') {
            $keterangan = [
                'Izin kegiatan organisasi',
                'Izin acara keluarga',
                'Izin lomba',
                'Izin mengikuti seminar',
            ];
        } elseif ($status === 'sakit') {
            $keterangan = [
                'Sakit demam',
                'Sakit flu',
                'Rawat inap',
                'Sakit perut',
            ];
        } else { // alpa
            $keterangan = [
                'Tidak ada keterangan',
                'Terlambat lebih dari 30 menit',
                'Tidak mengirim konfirmasi',
            ];
        }

        return $keterangan[array_rand($keterangan)];
    }
}
