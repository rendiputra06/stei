<?php

namespace Database\Seeders;

use App\Models\Kurikulum;
use App\Models\ProgramStudi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KurikulumSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $programStudi = ProgramStudi::where('nama', 'Ekonomi Islam')->first();

        $kurikulum = [
            [
                'kode' => 'KUR-EI-2021',
                'nama' => 'Kurikulum Ekonomi Islam 2021',
                'program_studi_id' => $programStudi->id,
                'tahun_mulai' => 2021,
                'status' => 'tidak aktif',
                'deskripsi' => 'Kurikulum lama program studi Ekonomi Islam',
                'is_active' => true,
            ],
            [
                'kode' => 'KUR-EI-2024',
                'nama' => 'Kurikulum Ekonomi Islam 2024',
                'program_studi_id' => $programStudi->id,
                'tahun_mulai' => 2024,
                'status' => 'aktif',
                'deskripsi' => 'Kurikulum baru program studi Ekonomi Islam',
                'is_active' => true,
            ],
        ];

        foreach ($kurikulum as $k) {
            Kurikulum::create($k);
        }
    }
}
