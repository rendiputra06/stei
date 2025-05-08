<?php

namespace Database\Seeders;

use App\Models\Jadwal;
use App\Models\MateriPerkuliahan;
use App\Models\Presensi;
use Illuminate\Database\Seeder;

class MateriPerkuliahanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil semua jadwal aktif
        $jadwals = Jadwal::where('is_active', true)->get();

        // Buat materi untuk setiap jadwal
        $jadwals->each(function ($jadwal) {
            // Ambil presensi untuk jadwal ini
            $presensi = Presensi::where('jadwal_id', $jadwal->id)->orderBy('pertemuan_ke')->get();

            // Buat materi untuk setiap presensi
            $presensi->each(function ($pertemuan) use ($jadwal) {
                // Buat materi perkuliahan
                MateriPerkuliahan::create([
                    'jadwal_id' => $jadwal->id,
                    'judul' => $this->generateJudul($jadwal, $pertemuan->pertemuan_ke),
                    'deskripsi' => $this->generateDeskripsi($pertemuan->pertemuan_ke),
                    'file_path' => null, // Tidak ada file fisik di seeder
                    'pertemuan_ke' => $pertemuan->pertemuan_ke,
                    'tanggal' => $pertemuan->tanggal,
                ]);
            });
        });
    }

    /**
     * Generate judul materi
     */
    private function generateJudul(Jadwal $jadwal, int $pertemuan): string
    {
        $mataKuliah = $jadwal->mataKuliah->nama;

        // Judul berdasarkan pertemuan
        if ($pertemuan === 1) {
            return "Pengantar " . $mataKuliah;
        } elseif ($pertemuan === 8) {
            return "Review Materi UTS " . $mataKuliah;
        } elseif ($pertemuan === 14) {
            return "Review Materi UAS " . $mataKuliah;
        } else {
            return "Materi " . $mataKuliah . " - Pertemuan " . $pertemuan;
        }
    }

    /**
     * Generate deskripsi materi
     */
    private function generateDeskripsi(int $pertemuan): string
    {
        // Deskripsi berdasarkan pertemuan
        if ($pertemuan === 1) {
            return "Pengenalan mata kuliah, kontrak perkuliahan, dan pembahasan silabus.";
        } elseif ($pertemuan === 8) {
            return "Review materi untuk persiapan Ujian Tengah Semester (UTS).";
        } elseif ($pertemuan === 14) {
            return "Review materi untuk persiapan Ujian Akhir Semester (UAS).";
        } else {
            $deskripsi = [
                "Materi ini membahas konsep dasar dan aplikasi praktis dalam bidang terkait.",
                "Pembahasan materi mencakup teori dan contoh kasus implementasi di dunia nyata.",
                "Mahasiswa akan mempelajari teknik dan metode yang digunakan dalam industri.",
                "Pendalaman materi dengan contoh-contoh soal dan latihan.",
                "Diskusi dan presentasi kelompok mengenai topik terkait.",
            ];

            return $deskripsi[array_rand($deskripsi)];
        }
    }
}
