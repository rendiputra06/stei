<?php

namespace Database\Seeders;

use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\Pembimbingan;
use Illuminate\Database\Seeder;

class PembimbinganSeeder extends Seeder
{
    public function run(): void
    {
        $dosen = Dosen::all();
        $mahasiswa = Mahasiswa::all();

        foreach ($dosen as $d) {
            $jumlahBimbingan = rand(0, 5);
            $mahasiswaRandom = $mahasiswa->random($jumlahBimbingan);

            foreach ($mahasiswaRandom as $m) {
                Pembimbingan::create([
                    'dosen_id' => $d->id,
                    'mahasiswa_id' => $m->id,
                ]);
            }
        }
    }
}
