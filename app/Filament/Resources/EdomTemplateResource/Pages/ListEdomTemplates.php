<?php

namespace App\Filament\Resources\EdomTemplateResource\Pages;

use App\Filament\Resources\EdomTemplateResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEdomTemplates extends ListRecords
{
    protected static string $resource = EdomTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
