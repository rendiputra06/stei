<?php

namespace App\Filament\Resources\StatusMahasiswaResource\Pages;

use App\Filament\Resources\StatusMahasiswaResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewStatusMahasiswa extends ViewRecord
{
    protected static string $resource = StatusMahasiswaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
