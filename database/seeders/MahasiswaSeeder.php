<?php

namespace Database\Seeders;

use App\Models\Mahasiswa;
use App\Models\ProgramStudi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MahasiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $programStudi = ProgramStudi::where('nama', 'Ekonomi Islam')->first();

        $mahasiswa = [
            [
                'nim' => '2023001',
                'nama' => 'Ananda Putra',
                'email' => 'ananda.putra@example.com',
                'no_telepon' => '081234567891',
                'alamat' => 'Jl. Mahasiswa No. 1, Jakarta',
                'tanggal_lahir' => '2000-05-15',
                'jenis_kelamin' => 'L',
                'program_studi_id' => $programStudi->id,
                'tahun_masuk' => 2023,
                'status' => 'aktif',
                'is_active' => true,
            ],
            [
                'nim' => '2023002',
                'nama' => 'Bella Safitri',
                'email' => 'bella.safitri@example.com',
                'no_telepon' => '081234567892',
                'alamat' => 'Jl. Mahasiswa No. 2, Jakarta',
                'tanggal_lahir' => '2001-07-22',
                'jenis_kelamin' => 'P',
                'program_studi_id' => $programStudi->id,
                'tahun_masuk' => 2023,
                'status' => 'aktif',
                'is_active' => true,
            ],
            [
                'nim' => '2022001',
                'nama' => 'Candra Wijaya',
                'email' => 'candra.wijaya@example.com',
                'no_telepon' => '081234567893',
                'alamat' => 'Jl. Mahasiswa No. 3, Jakarta',
                'tanggal_lahir' => '2000-02-12',
                'jenis_kelamin' => 'L',
                'program_studi_id' => $programStudi->id,
                'tahun_masuk' => 2022,
                'status' => 'aktif',
                'is_active' => true,
            ],
            [
                'nim' => '2022002',
                'nama' => 'Dina Maharani',
                'email' => 'dina.maharani@example.com',
                'no_telepon' => '081234567894',
                'alamat' => 'Jl. Mahasiswa No. 4, Jakarta',
                'tanggal_lahir' => '2001-10-05',
                'jenis_kelamin' => 'P',
                'program_studi_id' => $programStudi->id,
                'tahun_masuk' => 2022,
                'status' => 'aktif',
                'is_active' => true,
            ],
            [
                'nim' => '2021001',
                'nama' => 'Erik Kurniawan',
                'email' => 'erik.kurniawan@example.com',
                'no_telepon' => '081234567895',
                'alamat' => 'Jl. Mahasiswa No. 5, Jakarta',
                'tanggal_lahir' => '1999-12-30',
                'jenis_kelamin' => 'L',
                'program_studi_id' => $programStudi->id,
                'tahun_masuk' => 2021,
                'status' => 'aktif',
                'is_active' => true,
            ],
            [
                'nim' => '2021002',
                'nama' => 'Fitri Handayani',
                'email' => 'fitri.handayani@example.com',
                'no_telepon' => '081234567896',
                'alamat' => 'Jl. Mahasiswa No. 6, Jakarta',
                'tanggal_lahir' => '2000-08-17',
                'jenis_kelamin' => 'P',
                'program_studi_id' => $programStudi->id,
                'tahun_masuk' => 2021,
                'status' => 'cuti',
                'is_active' => true,
            ],
        ];

        foreach ($mahasiswa as $m) {
            Mahasiswa::create($m);
        }
    }
}
