<?php

namespace App\Filament\Dosen\Resources\KrsMahasiswaResource\Pages;

use App\Filament\Dosen\Resources\KrsMahasiswaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKrsMahasiswas extends ListRecords
{
    protected static string $resource = KrsMahasiswaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Tidak ada action tambahan di halaman list
        ];
    }
}
