<?php

namespace App\Filament\Dosen\Resources\KRSResource\RelationManagers;

use App\Models\Jadwal;
use App\Models\KRSDetail;
use App\Models\TahunAkademik;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class KRSDetailRelationManager extends RelationManager
{
    protected static string $relationship = 'krsDetail';

    protected static ?string $title = 'Mata Kuliah Yang Dipilih';

    protected static ?string $recordTitleAttribute = 'id';

    public function form(Form $form): Form
    {
        $tahunAkademik = TahunAkademik::getAktif();

        return $form
            ->schema([
                Forms\Components\Select::make('jadwal_id')
                    ->label('Pilih Mata Kuliah')
                    ->options(function () use ($tahunAkademik) {
                        if (!$tahunAkademik) {
                            return [];
                        }

                        return Jadwal::query()
                            ->where('tahun_akademik_id', $tahunAkademik->id)
                            ->with(['mataKuliah', 'dosen', 'ruangan'])
                            ->get()
                            ->mapWithKeys(function ($jadwal) {
                                return [
                                    $jadwal->id => "{$jadwal->mataKuliah->kode} - {$jadwal->mataKuliah->nama} - {$jadwal->kelas} ({$jadwal->mataKuliah->sks} SKS) - {$jadwal->dosen->nama}"
                                ];
                            });
                    })
                    ->searchable()
                    ->required()
                    ->live()
                    ->afterStateUpdated(function ($state, Forms\Set $set) {
                        if ($state) {
                            $jadwal = Jadwal::with('mataKuliah')->find($state);
                            if ($jadwal) {
                                $set('mata_kuliah_id', $jadwal->mata_kuliah_id);
                                $set('sks', $jadwal->mataKuliah->sks);
                                $set('kelas', $jadwal->kelas);
                            }
                        }
                    }),

                Forms\Components\Hidden::make('mata_kuliah_id')
                    ->required(),

                Forms\Components\TextInput::make('sks')
                    ->label('SKS')
                    ->numeric()
                    ->disabled()
                    ->dehydrated()
                    ->required(),

                Forms\Components\TextInput::make('kelas')
                    ->label('Kelas')
                    ->disabled()
                    ->dehydrated()
                    ->required(),

                Forms\Components\Select::make('status')
                    ->label('Status')
                    ->options(KRSDetail::getStatusList())
                    ->default('aktif')
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('mataKuliah.kode')
                    ->label('Kode MK')
                    ->searchable(),
                Tables\Columns\TextColumn::make('mataKuliah.nama')
                    ->label('Mata Kuliah')
                    ->searchable(),
                Tables\Columns\TextColumn::make('sks')
                    ->label('SKS'),
                Tables\Columns\TextColumn::make('jadwal.hari')
                    ->label('Hari'),
                Tables\Columns\TextColumn::make('jadwal.jam_mulai')
                    ->label('Jam Mulai')
                    ->formatStateUsing(fn(Carbon $state) => $state->format('H:i')),
                Tables\Columns\TextColumn::make('jadwal.jam_selesai')
                    ->label('Jam Selesai')
                    ->formatStateUsing(fn(Carbon $state) => $state->format('H:i')),
                Tables\Columns\TextColumn::make('jadwal.ruangan.nama')
                    ->label('Ruangan'),
                Tables\Columns\TextColumn::make('jadwal.dosen.nama')
                    ->label('Dosen'),
                Tables\Columns\TextColumn::make('kelas')
                    ->label('Kelas'),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'primary' => 'aktif',
                        'danger' => 'batal',
                    ])
                    ->formatStateUsing(fn($state) => KRSDetail::getStatusList()[$state] ?? $state),
            ])
            ->filters([
                // Tidak ada filter yang diperlukan
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Tambah Mata Kuliah')
                    ->visible(fn() => $this->getOwnerRecord()->isSubmitted())
                    ->modalHeading('Tambah Mata Kuliah')
                    ->successNotificationTitle('Mata kuliah berhasil ditambahkan')
                    ->mutateFormDataUsing(function (array $data): array {
                        $jadwal = Jadwal::findOrFail($data['jadwal_id']);

                        // Pastikan mata kuliah belum dipilih
                        $exists = KRSDetail::where('krs_id', $this->getOwnerRecord()->id)
                            ->where('mata_kuliah_id', $jadwal->mata_kuliah_id)
                            ->exists();

                        if ($exists) {
                            Notification::make()
                                ->title('Mata kuliah sudah dipilih')
                                ->danger()
                                ->send();

                            return [];
                        }

                        // Cek bentrok jadwal
                        $bentrok = $this->cekJadwalBentrok($jadwal);

                        if ($bentrok) {
                            Notification::make()
                                ->title('Jadwal bentrok')
                                ->body('Jadwal mata kuliah ini bentrok dengan mata kuliah lain yang sudah ada di KRS.')
                                ->danger()
                                ->send();

                            return [];
                        }

                        return $data;
                    })
                    ->after(function () {
                        // Update total SKS
                        $krs = $this->getOwnerRecord();
                        $krs->updateTotalSKS();
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->visible(fn() => $this->getOwnerRecord()->isSubmitted()),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn() => $this->getOwnerRecord()->isSubmitted())
                    ->successNotificationTitle('Mata kuliah berhasil dihapus')
                    ->after(function () {
                        // Update total SKS
                        $krs = $this->getOwnerRecord();
                        $krs->updateTotalSKS();
                    }),
            ])
            ->bulkActions([
                // Tidak ada bulk action yang diperlukan
            ])
            ->emptyStateHeading('Belum ada mata kuliah yang dipilih')
            ->emptyStateDescription('Mahasiswa belum menambahkan mata kuliah ke KRS ini.');
    }

    protected function cekJadwalBentrok($jadwal): bool
    {
        // Ambil detail KRS yang sudah ada
        $krsDetails = KRSDetail::where('krs_id', $this->getOwnerRecord()->id)
            ->where('status', 'aktif')
            ->with('jadwal')
            ->get();

        // Cek untuk setiap jadwal yang sudah ada
        foreach ($krsDetails as $krsDetail) {
            $existingJadwal = $krsDetail->jadwal;

            // Jika hari sama
            if ($existingJadwal->hari === $jadwal->hari) {
                // Cek apakah waktu bentrok
                $newStart = $jadwal->jam_mulai;
                $newEnd = $jadwal->jam_selesai;
                $existingStart = $existingJadwal->jam_mulai;
                $existingEnd = $existingJadwal->jam_selesai;

                // Bentrok jika:
                // 1. Jadwal baru dimulai di tengah jadwal yang sudah ada
                // 2. Jadwal baru selesai di tengah jadwal yang sudah ada
                // 3. Jadwal baru mencakup jadwal yang sudah ada
                if (
                    ($newStart >= $existingStart && $newStart < $existingEnd) ||
                    ($newEnd > $existingStart && $newEnd <= $existingEnd) ||
                    ($newStart <= $existingStart && $newEnd >= $existingEnd)
                ) {
                    return true;
                }
            }
        }

        return false;
    }
}
