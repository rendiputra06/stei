<?php

namespace Database\Seeders;

use App\Models\TahunAkademik;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TahunAkademikSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset status aktif
        TahunAkademik::query()->update(['aktif' => false]);

        // Tahun akademik 2023/2024 - Semester Ganjil (sudah selesai)
        TahunAkademik::create([
            'kode' => '20231',
            'tahun' => 2023,
            'semester' => 'Ganjil',
            'nama' => 'Semester Ganjil 2023/2024',
            'aktif' => false,
            'tanggal_mulai' => '2023-09-01',
            'tanggal_selesai' => '2024-01-31',
            'krs_mulai' => '2023-08-15 08:00:00',
            'krs_selesai' => '2023-08-28 23:59:59',
            'nilai_mulai' => '2024-01-10 08:00:00',
            'nilai_selesai' => '2024-01-25 23:59:59',
        ]);

        // Tahun akademik 2023/2024 - Semester Genap (sudah selesai)
        TahunAkademik::create([
            'kode' => '20232',
            'tahun' => 2023,
            'semester' => 'Genap',
            'nama' => 'Semester Genap 2023/2024',
            'aktif' => false,
            'tanggal_mulai' => '2024-02-01',
            'tanggal_selesai' => '2024-06-30',
            'krs_mulai' => '2024-01-20 08:00:00',
            'krs_selesai' => '2024-02-10 23:59:59',
            'nilai_mulai' => '2024-06-10 08:00:00',
            'nilai_selesai' => '2024-06-25 23:59:59',
        ]);

        // Tahun akademik 2023/2024 - Semester Pendek
        TahunAkademik::create([
            'kode' => '20233',
            'tahun' => 2023,
            'semester' => 'Pendek',
            'nama' => 'Semester Pendek 2023/2024',
            'aktif' => false,
            'tanggal_mulai' => '2024-07-01',
            'tanggal_selesai' => '2024-08-15',
            'krs_mulai' => '2024-06-25 08:00:00',
            'krs_selesai' => '2024-07-05 23:59:59',
            'nilai_mulai' => '2024-08-05 08:00:00',
            'nilai_selesai' => '2024-08-12 23:59:59',
        ]);

        // Tahun akademik 2024/2025 - Semester Ganjil (aktif saat ini)
        TahunAkademik::create([
            'kode' => '20241',
            'tahun' => 2024,
            'semester' => 'Ganjil',
            'nama' => 'Semester Ganjil 2024/2025',
            'aktif' => true,
            'tanggal_mulai' => '2024-09-01',
            'tanggal_selesai' => '2025-01-31',
            'krs_mulai' => '2024-08-15 08:00:00',
            'krs_selesai' => '2024-08-28 23:59:59',
            'nilai_mulai' => '2025-01-10 08:00:00',
            'nilai_selesai' => '2025-01-25 23:59:59',
        ]);
    }
}
