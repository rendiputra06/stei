<?php

namespace App\Filament\Resources\EdomPertanyaanResource\Pages;

use App\Filament\Resources\EdomPertanyaanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEdomPertanyaan extends EditRecord
{
    protected static string $resource = EdomPertanyaanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
