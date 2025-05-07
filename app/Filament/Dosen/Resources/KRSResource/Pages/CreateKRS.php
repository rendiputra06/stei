<?php

namespace App\Filament\Dosen\Resources\KRSResource\Pages;

use App\Filament\Dosen\Resources\KRSResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateKRS extends CreateRecord
{
    protected static string $resource = KRSResource::class;

    public function mount(): void
    {
        // Dosen tidak diperbolehkan membuat KRS baru
        // Redirect ke halaman daftar KRS dengan notifikasi
        Notification::make()
            ->title('Akses Ditolak')
            ->body('Dosen tidak diperbolehkan membuat KRS baru. KRS dibuat oleh mahasiswa.')
            ->danger()
            ->send();

        $this->redirect(KRSResource::getUrl('index'));
    }
}
