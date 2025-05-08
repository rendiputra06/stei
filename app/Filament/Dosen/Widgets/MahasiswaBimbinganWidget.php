<?php

namespace App\Filament\Dosen\Widgets;

use App\Models\Mahasiswa;
use App\Models\KRS;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MahasiswaBimbinganWidget extends BaseWidget
{
    protected static ?int $sort = 3;

    protected function getStats(): array
    {
        $dosenId = Auth::user()->dosen->id;

        // Hitung total mahasiswa bimbingan
        $totalMahasiswa = Mahasiswa::whereHas('pembimbingan', function ($query) use ($dosenId) {
            $query->where('dosen_id', $dosenId);
        })->count();

        // Hitung jumlah KRS yang menunggu persetujuan
        $pendingKrs = KRS::whereHas('mahasiswa', function ($query) use ($dosenId) {
            $query->whereHas('pembimbingan', function ($query) use ($dosenId) {
                $query->where('dosen_id', $dosenId);
            });
        })
            ->where('status', 'submitted')
            ->count();

        // Hitung distribusi status mahasiswa
        $statusMahasiswa = DB::table('mahasiswa')
            ->join('pembimbingan', 'mahasiswa.id', '=', 'pembimbingan.mahasiswa_id')
            ->join('status_mahasiswa', 'mahasiswa.status_mahasiswa_id', '=', 'status_mahasiswa.id')
            ->where('pembimbingan.dosen_id', $dosenId)
            ->select('status_mahasiswa.nama', DB::raw('count(*) as total'))
            ->groupBy('status_mahasiswa.nama')
            ->get()
            ->map(function ($item) use ($totalMahasiswa) {
                return [
                    'status' => $item->nama,
                    'total' => $item->total,
                    'percentage' => $totalMahasiswa > 0 ? round(($item->total / $totalMahasiswa) * 100, 2) : 0,
                ];
            })
            ->pluck('total', 'status')
            ->toArray();

        // Format data status untuk ditampilkan
        $statusList = [];
        foreach ($statusMahasiswa as $status => $count) {
            $statusList[] = "$status: $count";
        }
        $statusText = implode(' | ', $statusList);

        return [
            Stat::make('Total Mahasiswa Bimbingan', $totalMahasiswa)
                ->description('Jumlah mahasiswa yang dibimbing')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('primary'),

            Stat::make('Menunggu Persetujuan KRS', $pendingKrs)
                ->description('Jumlah KRS yang perlu disetujui')
                ->descriptionIcon('heroicon-m-document-text')
                ->color($pendingKrs > 0 ? 'warning' : 'success'),

            Stat::make('Status Mahasiswa', $statusText)
                ->description('Distribusi status mahasiswa bimbingan')
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color('info'),
        ];
    }
}
