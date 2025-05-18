<?php

namespace Database\Seeders;

use App\Models\EdomJadwal;
use App\Models\EdomPertanyaan;
use App\Models\EdomTemplate;
use App\Models\TahunAkademik;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EdomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Membuat Template EDOM
        $template = EdomTemplate::create([
            'nama_template' => 'Template Evaluasi Dosen dan Mata Kuliah',
            'deskripsi' => 'Template standar untuk evaluasi dosen dan mata kuliah',
            'is_aktif' => true,
        ]);

        // Membuat Pertanyaan EDOM dengan skala likert
        $pertanyaanLikert = [
            'Dosen menyampaikan materi perkuliahan dengan jelas',
            'Dosen memberikan contoh yang relevan dalam proses pembelajaran',
            'Dosen mampu menjelaskan keterkaitan antara teori dan praktik',
            'Dosen memberikan kesempatan bagi mahasiswa untuk bertanya',
            'Dosen memberikan umpan balik terhadap hasil kerja mahasiswa',
            'Dosen hadir tepat waktu dalam kegiatan perkuliahan',
            'Dosen menyelenggarakan perkuliahan sesuai dengan jumlah pertemuan yang dijadwalkan',
            'Materi yang diajarkan sesuai dengan silabus yang diberikan di awal perkuliahan',
            'Metode pembelajaran yang digunakan membantu pemahaman materi',
            'Penugasan yang diberikan meningkatkan pemahaman saya terhadap materi kuliah',
        ];

        // Buat pertanyaan skala likert
        foreach ($pertanyaanLikert as $index => $pertanyaan) {
            EdomPertanyaan::create([
                'template_id' => $template->id,
                'pertanyaan' => $pertanyaan,
                'jenis' => 'likert_scale',
                'urutan' => $index + 1,
                'is_required' => true,
            ]);
        }

        // Tambah pertanyaan teks
        EdomPertanyaan::create([
            'template_id' => $template->id,
            'pertanyaan' => 'Apa kelebihan dosen dalam mengajar mata kuliah ini?',
            'jenis' => 'text',
            'urutan' => count($pertanyaanLikert) + 1,
            'is_required' => false,
        ]);

        EdomPertanyaan::create([
            'template_id' => $template->id,
            'pertanyaan' => 'Apa saran Anda untuk meningkatkan kualitas pembelajaran mata kuliah ini?',
            'jenis' => 'text',
            'urutan' => count($pertanyaanLikert) + 2,
            'is_required' => false,
        ]);

        // Buat jadwal EDOM
        $tahunAkademik = TahunAkademik::latest('id')->first();

        if ($tahunAkademik) {
            // Jadwal tengah semester
            EdomJadwal::create([
                'tahun_akademik_id' => $tahunAkademik->id,
                'template_id' => $template->id,
                'nama_periode' => 'Evaluasi Tengah Semester ' . $tahunAkademik->nama_lengkap,
                'tanggal_mulai' => Carbon::now()->subDays(7),
                'tanggal_selesai' => Carbon::now()->addDays(7),
                'is_aktif' => true,
                'deskripsi' => 'Evaluasi tengah semester untuk dosen dan mata kuliah pada ' . $tahunAkademik->nama_lengkap,
            ]);

            // Jadwal akhir semester
            EdomJadwal::create([
                'tahun_akademik_id' => $tahunAkademik->id,
                'template_id' => $template->id,
                'nama_periode' => 'Evaluasi Akhir Semester ' . $tahunAkademik->nama_lengkap,
                'tanggal_mulai' => Carbon::now()->addMonth(2),
                'tanggal_selesai' => Carbon::now()->addMonth(2)->addDays(14),
                'is_aktif' => false,
                'deskripsi' => 'Evaluasi akhir semester untuk dosen dan mata kuliah pada ' . $tahunAkademik->nama_lengkap,
            ]);
        }
    }
}
