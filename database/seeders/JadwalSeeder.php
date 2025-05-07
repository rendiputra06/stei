<?php

namespace Database\Seeders;

use App\Models\Dosen;
use App\Models\Jadwal;
use App\Models\MataKuliah;
use App\Models\Ruangan;
use App\Models\TahunAkademik;
use Illuminate\Database\Seeder;

class JadwalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Mendapatkan data yang dibutuhkan
        $tahunAkademik = TahunAkademik::where('aktif', true)->first()
            ?? TahunAkademik::inRandomOrder()->first();

        if (!$tahunAkademik) {
            $this->command->info('Tidak ada Tahun Akademik yang tersedia. Membuat data baru...');
            $tahunAkademik = TahunAkademik::factory()->create(['aktif' => true]);
        }

        $mataKuliah = MataKuliah::where('is_active', true)->get();
        if ($mataKuliah->isEmpty()) {
            $this->command->info('Tidak ada Mata Kuliah yang tersedia. Membuat data baru...');
            $mataKuliah = MataKuliah::factory(10)->create(['is_active' => true]);
        }

        $dosen = Dosen::where('is_active', true)->get();
        if ($dosen->isEmpty()) {
            $this->command->info('Tidak ada Dosen yang tersedia. Membuat data baru...');
            $dosen = Dosen::factory(5)->create(['is_active' => true]);
        }

        $ruangan = Ruangan::where('is_active', true)->get();
        if ($ruangan->isEmpty()) {
            $this->command->info('Tidak ada Ruangan yang tersedia. Membuat data baru...');
            $ruangan = Ruangan::factory(5)->create(['is_active' => true]);
        }

        // Menghapus semua jadwal yang ada untuk tahun akademik ini
        Jadwal::where('tahun_akademik_id', $tahunAkademik->id)->delete();

        // Data hari dan jam
        $hari = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
        $waktu = [
            ['07:30:00', '09:10:00'], // Sesi 1
            ['09:30:00', '11:10:00'], // Sesi 2
            ['13:00:00', '14:40:00'], // Sesi 3
            ['15:00:00', '16:40:00'], // Sesi 4
        ];

        // Membuat jadwal untuk setiap mata kuliah
        foreach ($mataKuliah as $index => $mk) {
            // Mengambil dosen dan ruangan secara acak
            $dosenData = $dosen->random();
            $ruanganData = $ruangan->random();

            // Menentukan hari dan waktu secara acak
            $hariIndex = $index % count($hari);
            $waktuIndex = $index % count($waktu);

            // Membuat jadwal
            Jadwal::create([
                'tahun_akademik_id' => $tahunAkademik->id,
                'mata_kuliah_id' => $mk->id,
                'dosen_id' => $dosenData->id,
                'ruangan_id' => $ruanganData->id,
                'hari' => $hari[$hariIndex],
                'jam_mulai' => $waktu[$waktuIndex][0],
                'jam_selesai' => $waktu[$waktuIndex][1],
                'kelas' => chr(65 + ($index % 5)), // A, B, C, D, E
                'is_active' => true,
            ]);
        }

        // Tambahkan beberapa jadwal acak
        Jadwal::factory(10)->create([
            'tahun_akademik_id' => $tahunAkademik->id,
        ]);

        $this->command->info('Berhasil membuat ' . ($mataKuliah->count() + 10) . ' jadwal.');
    }
}
