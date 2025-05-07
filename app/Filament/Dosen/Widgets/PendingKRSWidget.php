<?php

namespace App\Filament\Dosen\Widgets;

use App\Filament\Dosen\Resources\KRSResource;
use App\Models\Dosen;
use App\Models\KRS;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class PendingKRSWidget extends BaseWidget
{
    protected static ?string $pollingInterval = '60s';

    protected function getStats(): array
    {
        // Ambil dosen berdasarkan user yang login
        $dosen = Dosen::where('user_id', Auth::id())->first();

        if (!$dosen) {
            return [
                Stat::make('KRS Menunggu Persetujuan', 0)
                    ->description('Anda tidak terdaftar sebagai dosen')
                    ->descriptionIcon('heroicon-o-x-circle')
                    ->color('danger'),
            ];
        }

        // Ambil mahasiswa bimbingan dosen
        $mahasiswaBimbinganIds = $dosen->mahasiswaBimbingan()->pluck('mahasiswa.id')->toArray();

        // Hitung jumlah KRS yang menunggu persetujuan
        $pending = KRS::whereIn('mahasiswa_id', $mahasiswaBimbinganIds)
            ->where('status', 'submitted')
            ->count();

        // Hitung jumlah KRS yang disetujui
        $approved = KRS::whereIn('mahasiswa_id', $mahasiswaBimbinganIds)
            ->where('status', 'approved')
            ->count();

        // Hitung jumlah KRS yang ditolak
        $rejected = KRS::whereIn('mahasiswa_id', $mahasiswaBimbinganIds)
            ->where('status', 'rejected')
            ->count();

        return [
            Stat::make('KRS Menunggu Persetujuan', $pending)
                ->description('Menunggu persetujuan Anda')
                ->descriptionIcon('heroicon-o-clock')
                ->color('warning')
                ->url(KRSResource::getUrl('index', ['tab' => 'menunggu']))
                ->extraAttributes([
                    'class' => 'cursor-pointer',
                ]),

            Stat::make('KRS Disetujui', $approved)
                ->description('Telah disetujui')
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('success')
                ->url(KRSResource::getUrl('index', ['tab' => 'disetujui']))
                ->extraAttributes([
                    'class' => 'cursor-pointer',
                ]),

            Stat::make('KRS Ditolak', $rejected)
                ->description('Telah ditolak')
                ->descriptionIcon('heroicon-o-x-circle')
                ->color('danger')
                ->url(KRSResource::getUrl('index', ['tab' => 'ditolak']))
                ->extraAttributes([
                    'class' => 'cursor-pointer',
                ]),
        ];
    }
}
