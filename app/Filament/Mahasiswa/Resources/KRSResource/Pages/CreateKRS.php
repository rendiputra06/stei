<?php

namespace App\Filament\Mahasiswa\Resources\KRSResource\Pages;

use App\Filament\Mahasiswa\Resources\KRSResource;
use App\Models\TahunAkademik;
use App\Models\Mahasiswa;
use App\Models\StatusMahasiswa;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class CreateKRS extends CreateRecord
{
    protected static string $resource = KRSResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $mahasiswa = Mahasiswa::where('user_id', Auth::id())->first();
        $tahunAkademik = TahunAkademik::getAktif();

        if (!$mahasiswa || !$tahunAkademik) {
            $this->halt();

            Notification::make()
                ->title('Error')
                ->body('Data mahasiswa atau tahun akademik tidak ditemukan')
                ->danger()
                ->send();

            return $data;
        }

        // Tambahkan data otomatis
        $data['mahasiswa_id'] = $mahasiswa->id;
        $data['tahun_akademik_id'] = $tahunAkademik->id;
        $data['semester'] = StatusMahasiswa::hitungSemester($mahasiswa, $tahunAkademik);
        $data['status'] = 'draft';
        $data['total_sks'] = 0;

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('edit', ['record' => $this->record]);
    }
}
