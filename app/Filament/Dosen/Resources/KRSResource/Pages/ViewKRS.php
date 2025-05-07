<?php

namespace App\Filament\Dosen\Resources\KRSResource\Pages;

use App\Filament\Dosen\Resources\KRSResource;
use App\Models\KRS;
use App\Models\TahunAkademik;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Pages\ViewRecord;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class ViewKRS extends ViewRecord
{
    protected static string $resource = KRSResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
                ->visible(fn(): bool => $this->record->isSubmitted()),

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

                    // TODO: Implementasi notifikasi email ke mahasiswa
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

                    // TODO: Implementasi notifikasi email ke mahasiswa
                }),

            Actions\Action::make('print')
                ->label('Cetak KRS')
                ->icon('heroicon-o-printer')
                ->url(fn(): string => route('krs.print', ['krs' => $this->record]))
                ->openUrlInNewTab(),
        ];
    }
}
