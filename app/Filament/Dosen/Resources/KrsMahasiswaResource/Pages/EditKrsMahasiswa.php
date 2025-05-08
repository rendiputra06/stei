<?php

namespace App\Filament\Dosen\Resources\KrsMahasiswaResource\Pages;

use App\Filament\Dosen\Resources\KrsMahasiswaResource;
use App\Models\KRS;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;

class EditKrsMahasiswa extends EditRecord
{
    protected static string $resource = KrsMahasiswaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Tidak ada action tambahan di header
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterSave(): void
    {
        // Jika status diubah menjadi approved atau rejected
        if ($this->record->isDirty('status') && ($this->record->isApproved() || $this->record->isRejected())) {
            $this->record->approved_by = Auth::id();
            $this->record->tanggal_approval = now();
            $this->record->save();
        }
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Jika KRS sudah disetujui atau ditolak, maka disable edit
        if ($this->record->isApproved() || $this->record->isRejected()) {
            $this->notification()->warning(
                'KRS ini sudah ' . ($this->record->isApproved() ? 'disetujui' : 'ditolak') . ' dan tidak dapat diubah.',
            );

            $this->redirect($this->getResource()::getUrl('view', ['record' => $this->record]));
        }

        return $data;
    }
}
