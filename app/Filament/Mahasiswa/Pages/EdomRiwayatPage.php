<?php

namespace App\Filament\Mahasiswa\Pages;

use App\Models\EdomPengisian;
use App\Models\Mahasiswa;
use Filament\Pages\Page;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Filament\Actions\Action;
use Filament\Notifications\Notification;

class EdomRiwayatPage extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-document-chart-bar';

    protected static ?string $navigationGroup = 'Evaluasi Dosen';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'Riwayat Evaluasi';

    protected static ?string $title = 'Riwayat Evaluasi Dosen';

    protected static string $view = 'filament.mahasiswa.pages.edom-riwayat-page';

    public function table(Table $table): Table
    {
        $mahasiswaId = Mahasiswa::where('user_id', Auth::id())->first()?->id;

        return $table
            ->query(
                EdomPengisian::query()
                    ->where('mahasiswa_id', $mahasiswaId)
                    ->with(['jadwal.tahunAkademik', 'mataKuliah', 'dosen'])
            )
            ->columns([
                TextColumn::make('jadwal.nama_periode')
                    ->label('Periode Evaluasi')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('jadwal.tahunAkademik.nama')
                    ->label('Tahun Akademik')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('mataKuliah.nama_mata_kuliah')
                    ->label('Mata Kuliah')
                    ->searchable(),
                TextColumn::make('dosen.nama')
                    ->label('Dosen')
                    ->searchable(),
                TextColumn::make('tanggal_pengisian')
                    ->label('Tanggal Pengisian')
                    ->date()
                    ->sortable(),
                BadgeColumn::make('status')
                    ->label('Status')
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'draft' => 'Draft',
                        'submitted' => 'Sudah Disubmit',
                        default => $state,
                    })
                    ->colors([
                        'warning' => 'draft',
                        'success' => 'submitted',
                    ]),
            ])
            ->filters([
                SelectFilter::make('jadwal_id')
                    ->label('Periode Evaluasi')
                    ->relationship('jadwal', 'nama_periode')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'draft' => 'Draft',
                        'submitted' => 'Sudah Disubmit',
                    ]),
            ])
            ->actions([
                \Filament\Tables\Actions\Action::make('lihat')
                    ->label('Lihat Detail')
                    ->icon('heroicon-o-eye')
                    ->url(fn(EdomPengisian $record): string => route('filament.mahasiswa.pages.isi-evaluasi-page', [
                        'jadwal_id' => $record->jadwal_id,
                        'jadwal_kuliah_id' => $record->jadwal_kuliah_id,
                        'dosen_id' => $record->dosen_id,
                        'mata_kuliah_id' => $record->mata_kuliah_id,
                    ]))
                    ->openUrlInNewTab(),
                \Filament\Tables\Actions\Action::make('lanjutkan')
                    ->label('Lanjutkan Pengisian')
                    ->icon('heroicon-o-pencil-square')
                    ->color('primary')
                    ->url(fn(EdomPengisian $record): string => route('filament.mahasiswa.pages.isi-evaluasi-page', [
                        'jadwal_id' => $record->jadwal_id,
                        'jadwal_kuliah_id' => $record->jadwal_kuliah_id,
                        'dosen_id' => $record->dosen_id,
                        'mata_kuliah_id' => $record->mata_kuliah_id,
                    ]))
                    ->visible(
                        fn(EdomPengisian $record): bool =>
                        $record->status === 'draft' &&
                            $record->jadwal->is_aktif &&
                            now()->between($record->jadwal->tanggal_mulai, $record->jadwal->tanggal_selesai)
                    ),
                \Filament\Tables\Actions\Action::make('submit')
                    ->label('Submit')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->action(function (EdomPengisian $record): void {
                        $record->status = 'submitted';
                        $record->save();

                        Notification::make()
                            ->title('Berhasil')
                            ->body('Evaluasi berhasil dikumpulkan.')
                            ->success()
                            ->send();
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Submit Evaluasi')
                    ->modalDescription('Setelah di-submit, evaluasi tidak dapat diubah lagi. Apakah Anda yakin ingin melanjutkan?')
                    ->modalSubmitActionLabel('Ya, Submit Sekarang')
                    ->visible(
                        fn(EdomPengisian $record): bool =>
                        $record->status === 'draft' &&
                            $record->jadwal->is_aktif &&
                            now()->between($record->jadwal->tanggal_mulai, $record->jadwal->tanggal_selesai)
                    ),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
