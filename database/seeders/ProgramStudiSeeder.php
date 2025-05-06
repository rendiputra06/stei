<?php

namespace Database\Seeders;

use App\Models\ProgramStudi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProgramStudiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $programStudi = [
            [
                'kode' => 'TI',
                'nama' => 'Teknik Informatika',
                'jenjang' => 'S1',
                'is_active' => true,
                'deskripsi' => 'Program Studi Teknik Informatika',
            ],
            [
                'kode' => 'SI',
                'nama' => 'Sistem Informasi',
                'jenjang' => 'S1',
                'is_active' => true,
                'deskripsi' => 'Program Studi Sistem Informasi',
            ],
            [
                'kode' => 'TK',
                'nama' => 'Teknik Komputer',
                'jenjang' => 'S1',
                'is_active' => true,
                'deskripsi' => 'Program Studi Teknik Komputer',
            ],
            [
                'kode' => 'MTI',
                'nama' => 'Magister Teknik Informatika',
                'jenjang' => 'S2',
                'is_active' => true,
                'deskripsi' => 'Program Studi Magister Teknik Informatika',
            ],
        ];

        foreach ($programStudi as $ps) {
            ProgramStudi::create($ps);
        }
    }
}
