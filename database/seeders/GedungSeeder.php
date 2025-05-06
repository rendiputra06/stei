<?php

namespace Database\Seeders;

use App\Models\Gedung;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GedungSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $gedung = [
            [
                'kode' => 'GD-A',
                'nama' => 'Gedung A',
                'lokasi' => 'Jl. Universitias No. 1',
                'is_active' => true,
                'deskripsi' => 'Gedung Perkuliahan Fakultas Teknik',
            ],
            [
                'kode' => 'GD-B',
                'nama' => 'Gedung B',
                'lokasi' => 'Jl. Universitias No. 1',
                'is_active' => true,
                'deskripsi' => 'Gedung Perkuliahan Fakultas Ekonomi',
            ],
            [
                'kode' => 'GD-C',
                'nama' => 'Gedung C',
                'lokasi' => 'Jl. Universitias No. 2',
                'is_active' => true,
                'deskripsi' => 'Gedung Laboratorium',
            ],
            [
                'kode' => 'GD-D',
                'nama' => 'Gedung D',
                'lokasi' => 'Jl. Universitias No. 2',
                'is_active' => true,
                'deskripsi' => 'Gedung Administrasi',
            ],
        ];

        foreach ($gedung as $g) {
            Gedung::create($g);
        }
    }
}
