<?php

namespace App\Http\Controllers;

use App\Models\KRS;
use App\Models\KRSDetail;
use App\Models\Mahasiswa;
use App\Models\TahunAkademik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TranskripCetakController extends Controller
{
    public function cetakTranskrip(Request $request)
    {
        // Ambil data mahasiswa dari user yang login
        $mahasiswa = Mahasiswa::where('user_id', Auth::id())->first();

        if (!$mahasiswa) {
            abort(404, 'Data mahasiswa tidak ditemukan.');
        }

        // Ambil data KRS Detail dan Nilai
        $krsDetails = KRSDetail::query()
            ->join('krs', 'krs_detail.krs_id', '=', 'krs.id')
            ->where('krs.mahasiswa_id', $mahasiswa->id)
            ->where('krs_detail.status', 'aktif')
            ->whereHas('nilai')
            ->with(['mataKuliah', 'nilai', 'krs'])
            ->select('krs_detail.*')
            ->orderBy('krs.semester')
            ->get();

        // Hitung IPK
        $totalSKS = 0;
        $totalBobot = 0;

        foreach ($krsDetails as $detail) {
            $sks = $detail->sks;
            $grade = $detail->nilai->grade ?? '';
            $bobot = $this->getNilaiBobot($grade);

            $totalSKS += $sks;
            $totalBobot += ($sks * $bobot);
        }

        $ipk = $totalSKS > 0 ? round($totalBobot / $totalSKS, 2) : 0;

        // Cari semester tertinggi
        $semesterTertinggi = KRS::where('mahasiswa_id', $mahasiswa->id)
            ->whereHas('krsDetail.nilai')
            ->max('semester') ?? 0;

        return view('cetak.transkrip', compact(
            'mahasiswa',
            'krsDetails',
            'totalSKS',
            'totalBobot',
            'ipk',
            'semesterTertinggi'
        ));
    }

    private function getNilaiBobot(string $grade): float
    {
        switch ($grade) {
            case 'A':
                return 4.0;
            case 'A-':
                return 3.7;
            case 'B+':
                return 3.3;
            case 'B':
                return 3.0;
            case 'B-':
                return 2.7;
            case 'C+':
                return 2.3;
            case 'C':
                return 2.0;
            case 'D':
                return 1.0;
            case 'E':
                return 0.0;
            default:
                return 0.0;
        }
    }
}
