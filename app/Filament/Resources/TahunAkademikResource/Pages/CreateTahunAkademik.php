<?php

namespace App\Filament\Resources\TahunAkademikResource\Pages;

use App\Filament\Resources\TahunAkademikResource;
use App\Models\TahunAkademik;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateTahunAkademik extends CreateRecord
{
    protected static string $resource = TahunAkademikResource::class;

    protected function afterCreate(): void
    {
        // Jika tahun akademik yang baru dibuat ditandai sebagai aktif
        if ($this->record->aktif) {
            // Nonaktifkan semua tahun akademik lainnya
            TahunAkademik::where('id', '!=', $this->record->id)
                ->update(['aktif' => false]);

            Notification::make()
                ->title('Tahun Akademik Aktif')
                ->body('Tahun akademik ini telah diaktifkan dan semua tahun akademik lainnya telah dinonaktifkan.')
                ->success()
                ->send();
        }
    }
}
