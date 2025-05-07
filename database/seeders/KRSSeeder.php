<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\KRS;
use App\Models\Mahasiswa;
use App\Models\TahunAkademik;
use App\Models\StatusMahasiswa;
use Carbon\Carbon;

class KRSSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil data yang diperlukan
        $mahasiswas = Mahasiswa::all();
        $tahunAkademik = TahunAkademik::getAktif();

        if (!$tahunAkademik) {
            $this->command->error('Tidak ada tahun akademik aktif!');
            return;
        }

        // Buat KRS untuk setiap mahasiswa dengan berbagai status
        foreach ($mahasiswas as $index => $mahasiswa) {
            // Cek apakah mahasiswa aktif di tahun akademik ini
            $statusMahasiswa = StatusMahasiswa::where('mahasiswa_id', $mahasiswa->id)
                ->where('tahun_akademik_id', $tahunAkademik->id)
                ->first();

            if (!$statusMahasiswa || $statusMahasiswa->status !== 'aktif') {
                continue;
            }

            // Cek apakah sudah ada KRS
            $existingKRS = KRS::where('mahasiswa_id', $mahasiswa->id)
                ->where('tahun_akademik_id', $tahunAkademik->id)
                ->first();

            if ($existingKRS) {
                continue; // Skip jika sudah ada KRS
            }

            // Tentukan status KRS berdasarkan index (untuk variasi)
            $status = 'draft';
            if ($index % 4 === 1) {
                $status = 'submitted';
            } else if ($index % 4 === 2) {
                $status = 'approved';
            } else if ($index % 4 === 3) {
                $status = 'rejected';
            }

            // Hitung semester
            $semester = StatusMahasiswa::hitungSemester($mahasiswa, $tahunAkademik);

            // Buat KRS
            $krs = KRS::create([
                'mahasiswa_id' => $mahasiswa->id,
                'tahun_akademik_id' => $tahunAkademik->id,
                'status' => $status,
                'semester' => $semester,
                'total_sks' => 0, // Akan diupdate setelah tambah KRSDetail
                'keterangan' => "KRS {$mahasiswa->nama} - Semester {$semester}",
            ]);

            // Set tanggal submit dan approval jika perlu
            if (in_array($status, ['submitted', 'approved', 'rejected'])) {
                $krs->tanggal_submit = Carbon::now()->subDays(rand(1, 10));
                $krs->save();
            }

            if (in_array($status, ['approved', 'rejected'])) {
                $krs->tanggal_approval = Carbon::now()->subDays(rand(1, 5));
                $krs->approved_by = 1; // Asumsi user ID 1 adalah admin
                $krs->save();
            }

            $this->command->info("Membuat KRS untuk {$mahasiswa->nama} dengan status {$status}");
        }
    }
}
