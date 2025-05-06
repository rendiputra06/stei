<?php

namespace Database\Seeders;

use App\Models\Kurikulum;
use App\Models\MataKuliah;
use App\Models\ProgramStudi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MataKuliahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $programStudi = ProgramStudi::where('nama', 'Ekonomi Islam')->first();
        $kurikulumLama = Kurikulum::where('kode', 'KUR-EI-2021')->first();
        $kurikulumBaru = Kurikulum::where('kode', 'KUR-EI-2024')->first();

        $mataKuliah = [
            // Mata kuliah kurikulum lama
            [
                'kode' => 'EI101',
                'nama' => 'Pengantar Ekonomi Islam',
                'sks' => 3,
                'semester' => 1,
                'jenis' => 'wajib',
                'kurikulum_id' => $kurikulumLama->id,
                'program_studi_id' => $programStudi->id,
                'deskripsi' => 'Mata kuliah dasar ekonomi Islam',
                'is_active' => true,
            ],
            [
                'kode' => 'EI102',
                'nama' => 'Akuntansi Dasar',
                'sks' => 3,
                'semester' => 1,
                'jenis' => 'wajib',
                'kurikulum_id' => $kurikulumLama->id,
                'program_studi_id' => $programStudi->id,
                'deskripsi' => 'Mata kuliah dasar akuntansi',
                'is_active' => true,
            ],
            [
                'kode' => 'EI201',
                'nama' => 'Fiqh Muamalah',
                'sks' => 2,
                'semester' => 2,
                'jenis' => 'wajib',
                'kurikulum_id' => $kurikulumLama->id,
                'program_studi_id' => $programStudi->id,
                'deskripsi' => 'Mata kuliah fiqh muamalah',
                'is_active' => true,
            ],
            [
                'kode' => 'EI301',
                'nama' => 'Perbankan Syariah',
                'sks' => 3,
                'semester' => 3,
                'jenis' => 'wajib',
                'kurikulum_id' => $kurikulumLama->id,
                'program_studi_id' => $programStudi->id,
                'deskripsi' => 'Mata kuliah perbankan syariah',
                'is_active' => true,
            ],

            // Mata kuliah kurikulum baru
            [
                'kode' => 'EI2101',
                'nama' => 'Pengantar Ekonomi Islam',
                'sks' => 4,
                'semester' => 1,
                'jenis' => 'wajib',
                'kurikulum_id' => $kurikulumBaru->id,
                'program_studi_id' => $programStudi->id,
                'deskripsi' => 'Mata kuliah dasar ekonomi Islam (revisi)',
                'is_active' => true,
            ],
            [
                'kode' => 'EI2102',
                'nama' => 'Akuntansi Syariah',
                'sks' => 3,
                'semester' => 1,
                'jenis' => 'wajib',
                'kurikulum_id' => $kurikulumBaru->id,
                'program_studi_id' => $programStudi->id,
                'deskripsi' => 'Mata kuliah akuntansi syariah',
                'is_active' => true,
            ],
            [
                'kode' => 'EI2103',
                'nama' => 'Islamic Finance',
                'sks' => 3,
                'semester' => 1,
                'jenis' => 'wajib',
                'kurikulum_id' => $kurikulumBaru->id,
                'program_studi_id' => $programStudi->id,
                'deskripsi' => 'Mata kuliah keuangan Islam',
                'is_active' => true,
            ],
            [
                'kode' => 'EI2201',
                'nama' => 'Fiqh Muamalah Kontemporer',
                'sks' => 3,
                'semester' => 2,
                'jenis' => 'wajib',
                'kurikulum_id' => $kurikulumBaru->id,
                'program_studi_id' => $programStudi->id,
                'deskripsi' => 'Mata kuliah fiqh muamalah kontemporer',
                'is_active' => true,
            ],
            [
                'kode' => 'EI2301',
                'nama' => 'Perbankan dan Asuransi Syariah',
                'sks' => 4,
                'semester' => 3,
                'jenis' => 'wajib',
                'kurikulum_id' => $kurikulumBaru->id,
                'program_studi_id' => $programStudi->id,
                'deskripsi' => 'Mata kuliah perbankan dan asuransi syariah',
                'is_active' => true,
            ],
            [
                'kode' => 'EI2401',
                'nama' => 'Financial Technology',
                'sks' => 3,
                'semester' => 4,
                'jenis' => 'pilihan',
                'kurikulum_id' => $kurikulumBaru->id,
                'program_studi_id' => $programStudi->id,
                'deskripsi' => 'Mata kuliah teknologi finansial',
                'is_active' => true,
            ],
        ];

        foreach ($mataKuliah as $mk) {
            MataKuliah::create($mk);
        }
    }
}
