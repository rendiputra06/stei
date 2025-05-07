<?php

namespace App\Filament\Mahasiswa\Resources\KRSResource\Pages;

use App\Filament\Mahasiswa\Resources\KRSResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewKRS extends ViewRecord
{
    protected static string $resource = KRSResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
                ->visible(fn() => $this->record->isDraftStatus() && KRSResource::dapatMengisiKRS()),
            Actions\Action::make('print')
                ->label('Cetak KRS')
                ->icon('heroicon-o-printer')
                ->url(fn() => route('krs.print', ['krs' => $this->record]))
                ->openUrlInNewTab(),
        ];
    }
}
