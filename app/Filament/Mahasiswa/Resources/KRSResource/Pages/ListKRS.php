<?php

namespace App\Filament\Mahasiswa\Resources\KRSResource\Pages;

use App\Filament\Mahasiswa\Resources\KRSResource;
use App\Models\TahunAkademik;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Notifications\Notification;
use Carbon\Carbon;

class ListKRS extends ListRecords
{
    protected static string $resource = KRSResource::class;

    protected function getHeaderActions(): array
    {
        $tahunAkademik = TahunAkademik::getAktif();
        $now = Carbon::now();
        $canCreateKRS = $tahunAkademik && $now->between($tahunAkademik->krs_mulai, $tahunAkademik->krs_selesai);

        return [
            Actions\CreateAction::make()
                ->visible(fn() => KRSResource::dapatMembuatKRS())
                ->beforeFormFilled(function () {
                    if (!KRSResource::dapatMembuatKRS()) {
                        $this->halt();

                        Notification::make()
                            ->title('Tidak dapat membuat KRS')
                            ->body('Anda tidak dapat membuat KRS karena periode pengisian KRS belum dimulai atau sudah berakhir, atau Anda sudah memiliki KRS di tahun akademik ini.')
                            ->danger()
                            ->send();
                    }
                }),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            // Jika perlu, tambahkan widget di sini
        ];
    }
}
