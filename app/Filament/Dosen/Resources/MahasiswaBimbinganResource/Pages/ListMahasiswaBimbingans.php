<?php

namespace App\Filament\Dosen\Resources\MahasiswaBimbinganResource\Pages;

use App\Filament\Dosen\Resources\MahasiswaBimbinganResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMahasiswaBimbingans extends ListRecords
{
    protected static string $resource = MahasiswaBimbinganResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Tidak ada action tambahan di halaman list
        ];
    }
}
