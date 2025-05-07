<?php

namespace App\Filament\Mahasiswa\Resources\KRSResource\Pages;

use App\Filament\Mahasiswa\Resources\KRSResource;
use App\Models\TahunAkademik;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;
use Carbon\Carbon;

class EditKRS extends EditRecord
{
    protected static string $resource = KRSResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make()
                ->visible(fn() => $this->record->isDraftStatus() && KRSResource::dapatMengisiKRS()),
            Actions\Action::make('submit')
                ->label('Ajukan KRS')
                ->icon('heroicon-o-paper-airplane')
                ->color('success')
                ->visible(fn() => $this->record->isDraftStatus() && KRSResource::dapatMengisiKRS())
                ->requiresConfirmation()
                ->modalHeading('Ajukan KRS')
                ->modalDescription('Anda yakin ingin mengajukan KRS ini? Setelah diajukan, Anda tidak dapat mengubah KRS lagi.')
                ->action(function () {
                    $record = $this->record;

                    // Cek jumlah SKS minimal
                    if ($record->total_sks < 12) {
                        Notification::make()
                            ->title('Total SKS minimal adalah 12')
                            ->body('Silakan tambahkan lebih banyak mata kuliah sebelum mengajukan KRS.')
                            ->danger()
                            ->send();
                        return;
                    }

                    // Cek jumlah SKS maksimal
                    if ($record->total_sks > 24) {
                        Notification::make()
                            ->title('Total SKS maksimal adalah 24')
                            ->body('Silakan kurangi jumlah mata kuliah sebelum mengajukan KRS.')
                            ->danger()
                            ->send();
                        return;
                    }

                    // Update status menjadi submitted
                    $record->status = 'submitted';
                    $record->tanggal_submit = now();
                    $record->save();

                    Notification::make()
                        ->title('KRS berhasil diajukan')
                        ->body('KRS anda telah berhasil diajukan dan menunggu persetujuan dari dosen wali.')
                        ->success()
                        ->send();

                    $this->redirect($this->getResource()::getUrl('index'));
                }),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Jika KRS tidak dalam status draft atau bukan dalam periode pengisian
        if (
            (isset($data['status']) && $data['status'] !== 'draft') ||
            !KRSResource::dapatMengisiKRS()
        ) {
            $this->isReadOnly(); // Set form ke mode readonly
        }

        return $data;
    }

    protected function beforeSave(): void
    {
        // Cek apakah KRS masih dalam status draft dan dalam periode pengisian
        if (!$this->record->isDraftStatus() || !KRSResource::dapatMengisiKRS()) {
            $this->halt();

            Notification::make()
                ->title('Tidak dapat menyimpan KRS')
                ->body('KRS tidak dapat disimpan karena status KRS bukan draft atau diluar periode pengisian KRS.')
                ->danger()
                ->send();
        }
    }

    protected function isReadOnly(): bool
    {
        // KRS dapat diedit jika dalam status draft dan dalam periode pengisian
        return !$this->record->isDraftStatus() || !KRSResource::dapatMengisiKRS();
    }
}
