<?php

namespace Database\Seeders;

use App\Models\KRSDetail;
use App\Models\Nilai;
use App\Models\PresensiDetail;
use Illuminate\Database\Seeder;

class NilaiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil semua KRS Detail yang aktif
        $krsDetails = KRSDetail::where('status', 'aktif')->get();

        // Buat nilai untuk setiap KRS Detail
        $krsDetails->each(function ($krsDetail) {
            // Hitung nilai kehadiran berdasarkan presensi
            $nilaiKehadiran = $this->hitungNilaiKehadiran($krsDetail);

            // Generate nilai acak yang wajar
            $nilaiTugas = $this->generateRandomNilai(65, 95);
            $nilaiUTS = $this->generateRandomNilai(60, 90);
            $nilaiUAS = $this->generateRandomNilai(60, 90);

            // Buat objek nilai
            $nilai = new Nilai([
                'krs_detail_id' => $krsDetail->id,
                'nilai_tugas' => $nilaiTugas,
                'nilai_uts' => $nilaiUTS,
                'nilai_uas' => $nilaiUAS,
                'nilai_kehadiran' => $nilaiKehadiran,
            ]);

            // Hitung nilai akhir dan grade
            $nilai->nilai_akhir = $nilai->hitungNilaiAkhir();
            $nilai->grade = $nilai->tentukanGrade();
            $nilai->keterangan = $this->generateKeterangan($nilai->grade);

            // Simpan nilai
            $nilai->save();
        });
    }

    /**
     * Hitung nilai kehadiran berdasarkan presensi
     */
    private function hitungNilaiKehadiran(KRSDetail $krsDetail): float
    {
        $mahasiswaId = $krsDetail->krs->mahasiswa_id;
        $jadwalId = $krsDetail->jadwal_id;

        // Dapatkan semua presensi untuk jadwal ini
        $totalPertemuan = \App\Models\Presensi::where('jadwal_id', $jadwalId)->count();

        if ($totalPertemuan === 0) {
            return 100; // Default jika belum ada presensi
        }

        // Hitung jumlah kehadiran
        $jumlahHadir = PresensiDetail::whereHas('presensi', function ($q) use ($jadwalId) {
            $q->where('jadwal_id', $jadwalId);
        })
            ->where('mahasiswa_id', $mahasiswaId)
            ->where('status', 'hadir')
            ->count();

        // Hitung persentase kehadiran
        $persentaseKehadiran = ($jumlahHadir / $totalPertemuan) * 100;

        return round($persentaseKehadiran, 2);
    }

    /**
     * Generate nilai acak yang wajar
     */
    private function generateRandomNilai(int $min = 60, int $max = 100): float
    {
        return round(rand($min * 100, $max * 100) / 100, 2);
    }

    /**
     * Generate keterangan berdasarkan grade
     */
    private function generateKeterangan(string $grade): ?string
    {
        $keterangan = [
            'A' => 'Sangat Baik',
            'A-' => 'Sangat Baik',
            'B+' => 'Baik',
            'B' => 'Baik',
            'B-' => 'Cukup Baik',
            'C+' => 'Cukup',
            'C' => 'Cukup',
            'D' => 'Kurang',
            'E' => 'Sangat Kurang',
        ];

        return $keterangan[$grade] ?? null;
    }
}
