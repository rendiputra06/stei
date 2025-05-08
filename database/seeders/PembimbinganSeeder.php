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
        // Hapus semua data pembimbingan yang ada
        Pembimbingan::truncate();

        $dosen = Dosen::all();
        $mahasiswa = Mahasiswa::all()->shuffle(); // Acak urutan mahasiswa

        // Buat array untuk melacak mahasiswa yang sudah mendapat pembimbing
        $mahasiswaTerpakai = [];

        foreach ($dosen as $d) {
            $jumlahBimbingan = rand(0, 5);

            // Filter mahasiswa yang belum memiliki pembimbing
            $mahasiswaTersedia = $mahasiswa->filter(function ($m) use ($mahasiswaTerpakai) {
                return !in_array($m->id, $mahasiswaTerpakai);
            });

            // Jika tidak ada mahasiswa tersedia lagi, lompati dosen ini
            if ($mahasiswaTersedia->isEmpty()) {
                continue;
            }

            // Batasi jumlah bimbingan berdasarkan mahasiswa yang tersedia
            $jumlahBimbingan = min($jumlahBimbingan, $mahasiswaTersedia->count());

            // Ambil mahasiswa secara acak dari mahasiswa yang tersedia
            $mahasiswaDipilih = $mahasiswaTersedia->take($jumlahBimbingan);

            foreach ($mahasiswaDipilih as $m) {
                Pembimbingan::create([
                    'dosen_id' => $d->id,
                    'mahasiswa_id' => $m->id,
                ]);

                // Tambahkan ke array mahasiswa yang sudah terpakai
                $mahasiswaTerpakai[] = $m->id;
            }
        }
    }
}
