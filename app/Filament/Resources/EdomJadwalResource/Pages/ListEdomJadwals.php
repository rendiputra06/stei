<?php

namespace App\Filament\Resources\EdomJadwalResource\Pages;

use App\Filament\Resources\EdomJadwalResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEdomJadwals extends ListRecords
{
    protected static string $resource = EdomJadwalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
