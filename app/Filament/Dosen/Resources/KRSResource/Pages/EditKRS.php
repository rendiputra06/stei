<?php

namespace App\Filament\Dosen\Resources\KRSResource\Pages;

use App\Filament\Dosen\Resources\KRSResource;
use App\Models\KRS;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class EditKRS extends EditRecord
{
    protected static string $resource = KRSResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),

            Actions\Action::make('approve')
                ->label('Setujui KRS')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->visible(fn(): bool => $this->record->isSubmitted())
                ->requiresConfirmation()
                ->modalHeading('Setujui KRS')
                ->modalDescription('Anda yakin ingin menyetujui KRS ini?')
                ->form([
                    Forms\Components\Textarea::make('catatan')
                        ->label('Catatan untuk Mahasiswa (opsional)')
                        ->placeholder('Masukkan catatan jika diperlukan'),
                ])
                ->action(function (array $data): void {
                    $record = $this->record;

                    // Update status menjadi approved
                    $record->status = 'approved';
                    $record->tanggal_approval = now();
                    $record->approved_by = Auth::id();

                    // Tambahkan catatan jika ada
                    if (!empty($data['catatan'])) {
                        $record->catatan_dosen = $data['catatan'];
                    }

                    $record->save();

                    // Kirim notifikasi
                    Notification::make()
                        ->title('KRS berhasil disetujui')
                        ->success()
                        ->send();

                    $this->redirect(KRSResource::getUrl('index'));
                }),

            Actions\Action::make('reject')
                ->label('Tolak KRS')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->visible(fn(): bool => $this->record->isSubmitted())
                ->requiresConfirmation()
                ->modalHeading('Tolak KRS')
                ->modalDescription('Anda yakin ingin menolak KRS ini?')
                ->form([
                    Forms\Components\Textarea::make('catatan')
                        ->label('Alasan Penolakan / Catatan')
                        ->placeholder('Masukkan alasan penolakan')
                        ->required(),
                ])
                ->action(function (array $data): void {
                    $record = $this->record;

                    // Update status menjadi rejected
                    $record->status = 'rejected';
                    $record->tanggal_approval = now();
                    $record->approved_by = Auth::id();
                    $record->catatan_dosen = $data['catatan'];
                    $record->save();

                    // Kirim notifikasi
                    Notification::make()
                        ->title('KRS telah ditolak')
                        ->success()
                        ->send();

                    $this->redirect(KRSResource::getUrl('index'));
                }),
        ];
    }

    protected function beforeSave(): void
    {
        // Hanya mengizinkan perubahan pada catatan_dosen
        if (!$this->record->isSubmitted()) {
            $this->halt();

            Notification::make()
                ->title('Tidak dapat menyimpan perubahan')
                ->body('Anda hanya dapat mengedit KRS yang berstatus "Diajukan".')
                ->danger()
                ->send();
        }
    }
}
