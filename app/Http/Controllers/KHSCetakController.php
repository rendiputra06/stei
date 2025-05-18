<?php

namespace App\Http\Controllers;

use App\Models\KRS;
use App\Models\KRSDetail;
use App\Models\Mahasiswa;
use App\Models\TahunAkademik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KHSCetakController extends Controller
{
    public function cetakKHS(Request $request, $semester = null, $tahunAkademikId = null)
    {
        // Jika tidak ada semester atau tahun akademik, redirect ke halaman KHS
        if (!$semester || !$tahunAkademikId) {
            return redirect()->route('filament.mahasiswa.pages.kartu-hasil-studi');
        }

        // Ambil data mahasiswa dari user yang login
        $mahasiswa = Mahasiswa::where('user_id', Auth::id())->first();

        if (!$mahasiswa) {
            abort(404, 'Data mahasiswa tidak ditemukan.');
        }

        // Ambil data tahun akademik
        $tahunAkademik = TahunAkademik::find($tahunAkademikId);

        if (!$tahunAkademik) {
            abort(404, 'Data tahun akademik tidak ditemukan.');
        }

        // Ambil data KRS Detail dan Nilai
        $krsDetails = KRSDetail::query()
            ->join('krs', 'krs_detail.krs_id', '=', 'krs.id')
            ->where('krs.mahasiswa_id', $mahasiswa->id)
            ->where('krs.semester', $semester)
            ->where('krs.tahun_akademik_id', $tahunAkademikId)
            ->where('krs_detail.status', 'aktif')
            ->whereHas('nilai')
            ->with(['mataKuliah', 'nilai'])
            ->select('krs_detail.*')
            ->get();

        // Hitung IP semester
        $totalSKS = 0;
        $totalBobot = 0;

        foreach ($krsDetails as $detail) {
            $sks = $detail->sks;
            $grade = $detail->nilai->grade ?? '';
            $bobot = 0;

            switch ($grade) {
                case 'A':
                    $bobot = 4.0;
                    break;
                case 'A-':
                    $bobot = 3.7;
                    break;
                case 'B+':
                    $bobot = 3.3;
                    break;
                case 'B':
                    $bobot = 3.0;
                    break;
                case 'B-':
                    $bobot = 2.7;
                    break;
                case 'C+':
                    $bobot = 2.3;
                    break;
                case 'C':
                    $bobot = 2.0;
                    break;
                case 'D':
                    $bobot = 1.0;
                    break;
                case 'E':
                    $bobot = 0;
                    break;
                default:
                    $bobot = 0;
            }

            $totalSKS += $sks;
            $totalBobot += ($sks * $bobot);
        }

        $ipSemester = $totalSKS > 0 ? round($totalBobot / $totalSKS, 2) : 0;

        return view('cetak.khs', compact(
            'mahasiswa',
            'tahunAkademik',
            'semester',
            'krsDetails',
            'totalSKS',
            'totalBobot',
            'ipSemester'
        ));
    }
}
