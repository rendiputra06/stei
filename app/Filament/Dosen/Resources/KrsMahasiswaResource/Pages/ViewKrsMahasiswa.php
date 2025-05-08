<?php

namespace App\Filament\Dosen\Resources\KrsMahasiswaResource\Pages;

use App\Filament\Dosen\Resources\KrsMahasiswaResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewKrsMahasiswa extends ViewRecord
{
    protected static string $resource = KrsMahasiswaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
                ->label('Setujui KRS')
                ->hidden(fn() => $this->record->isApproved() || $this->record->isRejected()),
            Actions\Action::make('print')
                ->label('Cetak KRS')
                ->icon('heroicon-o-printer')
                ->url(fn() => route('krs.print', $this->record))
                ->openUrlInNewTab(),
        ];
    }
}
