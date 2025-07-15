<?php

namespace App\Filament\Dosen\Resources\JadwalDosenResource\Pages;

use App\Filament\Dosen\Resources\JadwalDosenResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewJadwalDosen extends ViewRecord
{
    protected static string $resource = JadwalDosenResource::class;
    protected static string $view = 'filament.dosen.resources.jadwal-dosen-resource.pages.view-jadwal-dosen';

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('presensi')
                ->label('Kelola Presensi')
                ->icon('heroicon-o-clipboard-document-check')
                ->url(fn() => JadwalDosenResource::getUrl('presensi', ['record' => $this->record])),
            Actions\Action::make('materi')
                ->label('Kelola Materi')
                ->icon('heroicon-o-document-text')
                ->url(fn() => JadwalDosenResource::getUrl('materi', ['record' => $this->record])),
            Actions\Action::make('nilai')
                ->label('Kelola Nilai')
                ->icon('heroicon-o-clipboard-document-list')
                ->url(fn() => JadwalDosenResource::getUrl('nilai', ['record' => $this->record])),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Load related data jika belum di-load
        if (!isset($data['mataKuliah']) && $this->record) {
            $this->record->load(['mataKuliah', 'ruangan', 'tahunAkademik', 'dosen']);

            // Update form data manual jika relasi tersedia
            if ($this->record->mataKuliah) {
                $data['mataKuliah.nama'] = $this->record->mataKuliah->nama;
                $data['mataKuliah.kode'] = $this->record->mataKuliah->kode;
                $data['mataKuliah.sks'] = $this->record->mataKuliah->sks;
            }

            if ($this->record->tahunAkademik) {
                $data['tahunAkademik.nama'] = $this->record->tahunAkademik->nama;
            }

            if ($this->record->ruangan) {
                $data['ruangan.nama'] = $this->record->ruangan->nama;
            }
        }

        return $data;
    }

    public function getViewData(): array
    {
        // Pastikan relasi sudah di-load agar tidak N+1
        $this->record->load(['mataKuliah', 'ruangan', 'tahunAkademik', 'dosen']);
        return [
            'record' => $this->record,
        ];
    }
}
