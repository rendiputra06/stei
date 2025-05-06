<?php

namespace App\Filament\Resources\TahunAkademikResource\Pages;

use App\Filament\Resources\TahunAkademikResource;
use App\Models\TahunAkademik;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class EditTahunAkademik extends EditRecord
{
    protected static string $resource = TahunAkademikResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        // Jika tahun akademik yang diedit ditandai sebagai aktif
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
