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
                'kode' => 'EI',
                'nama' => 'Ekonomi Islam',
                'jenjang' => 'S1',
                'is_active' => true,
                'deskripsi' => 'Program Studi Ekonomi Islam',
            ],
        ];

        foreach ($programStudi as $ps) {
            ProgramStudi::create($ps);
        }
    }
}
