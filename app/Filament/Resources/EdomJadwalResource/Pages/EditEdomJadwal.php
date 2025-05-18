<?php

namespace App\Filament\Resources\EdomJadwalResource\Pages;

use App\Filament\Resources\EdomJadwalResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEdomJadwal extends EditRecord
{
    protected static string $resource = EdomJadwalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
