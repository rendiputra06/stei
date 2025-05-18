<?php

namespace App\Filament\Mahasiswa\Resources\EdomPengisianResource\Pages;

use App\Filament\Mahasiswa\Resources\EdomPengisianResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateEdomPengisian extends CreateRecord
{
    protected static string $resource = EdomPengisianResource::class;

    protected function afterCreate(): void
    {
        // Ambil data jawaban dari form
        $jawaban = $this->data['jawaban'] ?? [];

        // Simpan jawaban
        EdomPengisianResource::saveJawaban($this->record, $jawaban);
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
