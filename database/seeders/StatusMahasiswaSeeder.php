<?php

namespace Database\Seeders;

use App\Models\Mahasiswa;
use App\Models\StatusMahasiswa;
use App\Models\TahunAkademik;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StatusMahasiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil semua mahasiswa
        $mahasiswas = Mahasiswa::all();

        // Ambil semua tahun akademik
        $tahunAkademiks = TahunAkademik::all();

        // Untuk setiap mahasiswa
        foreach ($mahasiswas as $mahasiswa) {
            $semester = 1;

            // Untuk setiap tahun akademik
            foreach ($tahunAkademiks as $tahunAkademik) {
                // Hitung semester berdasarkan tahun akademik dan tahun masuk
                $semester = StatusMahasiswa::hitungSemester($mahasiswa, $tahunAkademik);

                // Hitung IPK acak
                $ipSemester = mt_rand(200, 400) / 100; // 2.00 sampai 4.00
                $ipk = mt_rand(200, 400) / 100; // 2.00 sampai 4.00

                // Hitung SKS
                $sksSemester = mt_rand(18, 24);
                $sksTotal = $semester * mt_rand(18, 24);

                // Tentukan status berdasarkan semester
                $status = 'tidak_aktif';
                $keterangan = 'Mahasiswa belum melakukan registrasi';

                // 60% mahasiswa aktif
                if (mt_rand(1, 100) <= 60) {
                    $status = 'aktif';
                    $keterangan = 'Mahasiswa aktif mengikuti perkuliahan';
                }

                // 10% mahasiswa cuti
                if (mt_rand(1, 100) <= 10) {
                    $status = 'cuti';
                    $keterangan = 'Cuti karena alasan pribadi';
                }

                // Jika semester 8, 70% lulus
                if ($semester >= 8 && mt_rand(1, 100) <= 70) {
                    $status = 'lulus';
                    $keterangan = 'Lulus dengan predikat memuaskan';
                }

                // 5% mahasiswa drop out jika semester > 4
                if ($semester > 4 && mt_rand(1, 100) <= 5) {
                    $status = 'drop_out';
                    $keterangan = 'Drop out karena nilai rendah';
                }

                // Buat status mahasiswa
                StatusMahasiswa::create([
                    'mahasiswa_id' => $mahasiswa->id,
                    'tahun_akademik_id' => $tahunAkademik->id,
                    'status' => $status,
                    'semester' => $semester,
                    'ip_semester' => $ipSemester,
                    'ipk' => $ipk,
                    'sks_semester' => $sksSemester,
                    'sks_total' => $sksTotal,
                    'keterangan' => $keterangan,
                ]);

                // Jika mahasiswa sudah lulus atau DO, tidak perlu tambah semester lagi
                if (in_array($status, ['lulus', 'drop_out'])) {
                    break;
                }
            }
        }
    }
}
