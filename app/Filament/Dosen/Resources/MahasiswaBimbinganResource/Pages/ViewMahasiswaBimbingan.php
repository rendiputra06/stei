<?php

namespace App\Filament\Dosen\Resources\MahasiswaBimbinganResource\Pages;

use App\Filament\Dosen\Resources\MahasiswaBimbinganResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewMahasiswaBimbingan extends ViewRecord
{
    protected static string $resource = MahasiswaBimbinganResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('lihat_krs')
                ->label('Lihat KRS')
                ->icon('heroicon-o-document-text')
                ->url(fn() => route('filament.dosen.resources.krs-mahasiswas.index', ['tableSearch' => $this->record->nim])),
        ];
    }
}
