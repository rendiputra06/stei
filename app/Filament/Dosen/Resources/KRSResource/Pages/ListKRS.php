<?php

namespace App\Filament\Dosen\Resources\KRSResource\Pages;

use App\Filament\Dosen\Resources\KRSResource;
use App\Models\KRS;
use App\Models\TahunAkademik;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListKRS extends ListRecords
{
    protected static string $resource = KRSResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Tidak perlu fitur Create bagi dosen
        ];
    }

    public function getTabs(): array
    {
        return [
            'semua' => Tab::make('Semua KRS'),
            'menunggu' => Tab::make('Menunggu Persetujuan')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', 'submitted')),
            'disetujui' => Tab::make('Disetujui')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', 'approved')),
            'ditolak' => Tab::make('Ditolak')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', 'rejected')),
            'draft' => Tab::make('Draft')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', 'draft')),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            // Jika perlu, tambahkan widget di sini
        ];
    }
}
