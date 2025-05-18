<?php

namespace App\Filament\Resources\EdomPertanyaanResource\Pages;

use App\Filament\Resources\EdomPertanyaanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEdomPertanyaans extends ListRecords
{
    protected static string $resource = EdomPertanyaanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
