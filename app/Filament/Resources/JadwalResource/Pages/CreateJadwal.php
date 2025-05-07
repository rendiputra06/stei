<?php

namespace App\Filament\Resources\JadwalResource\Pages;

use App\Filament\Resources\JadwalResource;
use App\Models\Jadwal;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateJadwal extends CreateRecord
{
    protected static string $resource = JadwalResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $jadwal = new Jadwal($data);

        // Validasi bentrok sebelum menyimpan
        if ($jadwal->isBentrokDosen()) {
            $this->halt('Jadwal bentrok dengan jadwal dosen yang sudah ada.');
        }

        if ($jadwal->isBentrokRuangan()) {
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
