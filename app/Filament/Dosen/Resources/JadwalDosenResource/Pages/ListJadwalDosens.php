<?php

namespace App\Filament\Dosen\Resources\JadwalDosenResource\Pages;

use App\Filament\Dosen\Resources\JadwalDosenResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListJadwalDosens extends ListRecords
{
    protected static string $resource = JadwalDosenResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Tidak ada action tambahan di halaman list
        ];
    }
}
