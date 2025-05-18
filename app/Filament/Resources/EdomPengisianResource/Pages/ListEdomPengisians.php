<?php

namespace App\Filament\Resources\EdomPengisianResource\Pages;

use App\Filament\Resources\EdomPengisianResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEdomPengisians extends ListRecords
{
    protected static string $resource = EdomPengisianResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
