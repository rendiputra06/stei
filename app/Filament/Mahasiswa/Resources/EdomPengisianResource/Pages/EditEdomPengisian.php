<?php

namespace App\Filament\Mahasiswa\Resources\EdomPengisianResource\Pages;

use App\Filament\Mahasiswa\Resources\EdomPengisianResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEdomPengisian extends EditRecord
{
    protected static string $resource = EdomPengisianResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $record = $this->getRecord();

        // Ambil data jawaban yang sudah ada
        $jawaban = [];
        foreach ($record->detail as $detail) {
            if ($detail->nilai !== null) {
                $jawaban[$detail->pertanyaan_id]['nilai'] = $detail->nilai;
            }

            if ($detail->jawaban_text !== null) {
                $jawaban[$detail->pertanyaan_id]['jawaban_text'] = $detail->jawaban_text;
            }
        }

        $data['jawaban'] = $jawaban;

        return $data;
    }

    protected function afterSave(): void
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
