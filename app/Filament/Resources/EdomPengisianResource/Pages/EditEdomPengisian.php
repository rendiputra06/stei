<?php

namespace App\Filament\Resources\EdomPengisianResource\Pages;

use App\Filament\Resources\EdomPengisianResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEdomPengisian extends EditRecord
{
    protected static string $resource = EdomPengisianResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
