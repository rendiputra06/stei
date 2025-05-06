<?php

namespace App\Filament\Resources\StatusMahasiswaResource\Pages;

use App\Filament\Resources\StatusMahasiswaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStatusMahasiswa extends EditRecord
{
    protected static string $resource = StatusMahasiswaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
