<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\KRS;
use App\Models\KRSDetail;
use App\Models\Jadwal;
use App\Models\TahunAkademik;

class KRSDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil data yang diperlukan
        $tahunAkademik = TahunAkademik::getAktif();

        if (!$tahunAkademik) {
            $this->command->error('Tidak ada tahun akademik aktif!');
            return;
        }

        $jadwals = Jadwal::where('tahun_akademik_id', $tahunAkademik->id)->get();

        if ($jadwals->isEmpty()) {
            $this->command->error('Tidak ada jadwal di tahun akademik aktif!');
            return;
        }

        // Ambil semua KRS di tahun akademik ini
        $allKRS = KRS::where('tahun_akademik_id', $tahunAkademik->id)->get();

        foreach ($allKRS as $krs) {
            // Skip jika sudah ada detail KRS
            if (KRSDetail::where('krs_id', $krs->id)->exists()) {
                continue;
            }

            // Ambil beberapa jadwal acak untuk semester ini
            // Filter jadwal berdasarkan semester (asumsi: semester ganjil/genap)
            $semesterType = $krs->semester % 2 === 1 ? 'ganjil' : 'genap';
            $semesterJadwals = $jadwals->filter(function ($jadwal) use ($semesterType) {
                return $jadwal->mataKuliah->semester_matakuliah % 2 === ($semesterType === 'ganjil' ? 1 : 0);
            });

            // Jika tidak ada jadwal untuk semester ini, gunakan semua jadwal
            if ($semesterJadwals->isEmpty()) {
                $semesterJadwals = $jadwals;
            }

            // Ambil 4-8 jadwal acak
            $jumlahMK = rand(4, 8);
            $selectedJadwals = $semesterJadwals->random(min($jumlahMK, $semesterJadwals->count()));

            // Tambahkan detail KRS untuk jadwal yang dipilih
            foreach ($selectedJadwals as $jadwal) {
                // Cek apakah mata kuliah sudah dipilih
                $exists = KRSDetail::where('krs_id', $krs->id)
                    ->where('mata_kuliah_id', $jadwal->mata_kuliah_id)
                    ->exists();

                if ($exists) {
                    continue;
                }

                KRSDetail::create([
                    'krs_id' => $krs->id,
                    'jadwal_id' => $jadwal->id,
                    'mata_kuliah_id' => $jadwal->mata_kuliah_id,
                    'sks' => $jadwal->mataKuliah->sks,
                    'kelas' => $jadwal->kelas,
                    'status' => 'aktif',
                ]);

                $this->command->info("Menambahkan {$jadwal->mataKuliah->nama} ke KRS {$krs->id}");
            }

            // Update total SKS
            $krs->updateTotalSKS();
        }
    }
}
