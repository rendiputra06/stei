<?php

namespace App\Filament\Resources\JadwalResource\Pages;

use App\Filament\Resources\JadwalResource;
use App\Models\Jadwal;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditJadwal extends EditRecord
{
    protected static string $resource = JadwalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $jadwal = Jadwal::find($record->id);
        $jadwal->fill($data);

        // Validasi bentrok sebelum menyimpan
        if ($jadwal->isBentrokDosen($record->id)) {
            $this->halt('Jadwal bentrok dengan jadwal dosen yang sudah ada.');
        }

        if ($jadwal->isBentrokRuangan($record->id)) {
            $this->halt('Jadwal bentrok dengan penggunaan ruangan yang sudah ada.');
        }

        $jadwal->save();
        return $jadwal;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
