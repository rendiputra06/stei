<?php

namespace Database\Seeders;

use App\Models\Ruangan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RuanganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ruangan = [
            [
                'kode' => 'A101',
                'nama' => 'Ruang A-101',
                'gedung_id' => 1, // Gedung A
                'program_studi_id' => 1, // Teknik Informatika
                'lantai' => 1,
                'kapasitas' => 40,
                'is_active' => true,
                'jenis' => 'kelas',
                'deskripsi' => 'Ruang Kelas Gedung A Lantai 1',
            ],
            [
                'kode' => 'A201',
                'nama' => 'Ruang A-201',
                'gedung_id' => 1, // Gedung A
                'program_studi_id' => 1, // Teknik Informatika
                'lantai' => 2,
                'kapasitas' => 30,
                'is_active' => true,
                'jenis' => 'kelas',
                'deskripsi' => 'Ruang Kelas Gedung A Lantai 2',
            ],
            [
                'kode' => 'B101',
                'nama' => 'Ruang B-101',
                'gedung_id' => 2, // Gedung B
                'program_studi_id' => 2, // Sistem Informasi
                'lantai' => 1,
                'kapasitas' => 35,
                'is_active' => true,
                'jenis' => 'kelas',
                'deskripsi' => 'Ruang Kelas Gedung B Lantai 1',
            ],
            [
                'kode' => 'C101',
                'nama' => 'Lab C-101',
                'gedung_id' => 3, // Gedung C
                'program_studi_id' => 1, // Teknik Informatika
                'lantai' => 1,
                'kapasitas' => 25,
                'is_active' => true,
                'jenis' => 'laboratorium',
                'deskripsi' => 'Laboratorium Komputer',
            ],
            [
                'kode' => 'C201',
                'nama' => 'Lab C-201',
                'gedung_id' => 3, // Gedung C
                'program_studi_id' => 3, // Teknik Komputer
                'lantai' => 2,
                'kapasitas' => 20,
                'is_active' => true,
                'jenis' => 'laboratorium',
                'deskripsi' => 'Laboratorium Jaringan',
            ],
            [
                'kode' => 'D101',
                'nama' => 'Kantor D-101',
                'gedung_id' => 4, // Gedung D
                'program_studi_id' => null,
                'lantai' => 1,
                'kapasitas' => 10,
                'is_active' => true,
                'jenis' => 'kantor',
                'deskripsi' => 'Ruang Administrasi',
            ],
        ];

        foreach ($ruangan as $r) {
            Ruangan::create($r);
        }
    }
}
