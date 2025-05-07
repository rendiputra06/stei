<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\StatusMahasiswa;
use App\Models\TahunAkademik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatusMahasiswaController extends Controller
{
    /**
     * Generate status mahasiswa berdasarkan tahun akademik
     */
    public function generateStatus(Request $request)
    {
        $request->validate([
            'tahun_akademik_id' => 'required|exists:tahun_akademik,id',
        ]);

        $tahunAkademikId = $request->input('tahun_akademik_id');
        $tahunAkademik = TahunAkademik::findOrFail($tahunAkademikId);

        // Default status untuk mahasiswa yang belum memiliki status
        $defaultStatus = 'aktif';

        // Mulai transaksi
        DB::beginTransaction();
        try {
            // Ambil semua mahasiswa aktif
            $mahasiswas = Mahasiswa::where('status', 'aktif')->get();
            $counter = 0;

            foreach ($mahasiswas as $mahasiswa) {
                // Periksa apakah status sudah ada untuk tahun akademik ini
                $statusExists = StatusMahasiswa::where('mahasiswa_id', $mahasiswa->id)
                    ->where('tahun_akademik_id', $tahunAkademikId)
                    ->exists();

                // Jika belum ada, buat status baru
                if (!$statusExists) {
                    // Hitung semester berdasarkan tahun masuk
                    $semester = StatusMahasiswa::hitungSemester($mahasiswa, $tahunAkademik);

                    // Cari status sebelumnya untuk mendapatkan IPK dan total SKS
                    $statusSebelumnya = StatusMahasiswa::where('mahasiswa_id', $mahasiswa->id)
                        ->orderBy('created_at', 'desc')
                        ->first();

                    StatusMahasiswa::create([
                        'mahasiswa_id' => $mahasiswa->id,
                        'tahun_akademik_id' => $tahunAkademikId,
                        'status' => $defaultStatus,
                        'semester' => $semester,
                        'ip_semester' => 0, // Default, akan diupdate nanti
                        'ipk' => $statusSebelumnya ? $statusSebelumnya->ipk : 0,
                        'sks_semester' => 0, // Default, akan diupdate nanti
                        'sks_total' => $statusSebelumnya ? $statusSebelumnya->sks_total : 0,
                        'keterangan' => 'Auto-generated status',
                    ]);

                    $counter++;
                }
            }

            DB::commit();
            return redirect()->back()->with('success', "Berhasil generate status untuk {$counter} mahasiswa");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal generate status: ' . $e->getMessage());
        }
    }
}
