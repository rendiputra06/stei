<?php

namespace Database\Seeders;

use App\Models\Dosen;
use App\Models\ProgramStudi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DosenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $programStudi = ProgramStudi::where('nama', 'Ekonomi Islam')->first();

        $dosen = [
            [
                'nip' => '19800101001',
                'nama' => 'Dr. Budi Santoso, M.Kom',
                'email' => 'budi.santoso@example.com',
                'no_telepon' => '081234567890',
                'alamat' => 'Jl. Contoh No. 123, Jakarta',
                'tanggal_lahir' => '1980-01-01',
                'jenis_kelamin' => 'L',
                'program_studi_id' => $programStudi->id,
                'is_active' => true,
            ],
            [
                'nip' => '19820202002',
                'nama' => 'Dr. Siti Rahma, M.Si',
                'email' => 'siti.rahma@example.com',
                'no_telepon' => '082345678901',
                'alamat' => 'Jl. Contoh No. 456, Jakarta',
                'tanggal_lahir' => '1982-02-02',
                'jenis_kelamin' => 'P',
                'program_studi_id' => $programStudi->id,
                'is_active' => true,
            ],
            [
                'nip' => '19790303003',
                'nama' => 'Prof. Ahmad Hidayat, Ph.D',
                'email' => 'ahmad.hidayat@example.com',
                'no_telepon' => '083456789012',
                'alamat' => 'Jl. Contoh No. 789, Jakarta',
                'tanggal_lahir' => '1979-03-03',
                'jenis_kelamin' => 'L',
                'program_studi_id' => $programStudi->id,
                'is_active' => true,
            ],
            [
                'nip' => '19850404004',
                'nama' => 'Dewi Anggraini, M.Kom',
                'email' => 'dewi.anggraini@example.com',
                'no_telepon' => '084567890123',
                'alamat' => 'Jl. Contoh No. 101, Jakarta',
                'tanggal_lahir' => '1985-04-04',
                'jenis_kelamin' => 'P',
                'program_studi_id' => $programStudi->id,
                'is_active' => true,
            ],
        ];

        foreach ($dosen as $d) {
            Dosen::create($d);
        }
    }
}
