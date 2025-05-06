<?php

namespace App\Filament\Resources\StatusMahasiswaResource\Pages;

use App\Filament\Resources\StatusMahasiswaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStatusMahasiswas extends ListRecords
{
    protected static string $resource = StatusMahasiswaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
