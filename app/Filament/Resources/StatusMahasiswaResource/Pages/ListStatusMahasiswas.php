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
            Actions\Action::make('generate')
                ->label('Generate Status')
                ->icon('heroicon-o-cpu-chip')
                ->color('success')
                ->url(static::getResource()::getUrl('generate')),
        ];
    }
}
