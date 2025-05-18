<?php

namespace App\Filament\Mahasiswa\Resources;

use App\Filament\Mahasiswa\Resources\EdomPengisianResource\Pages;
use App\Filament\Mahasiswa\Resources\EdomPengisianResource\RelationManagers;
use App\Models\EdomJadwal;
use App\Models\EdomPengisian;
use App\Models\EdomPengisianDetail;
use App\Models\EdomPertanyaan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\DB;
use App\Models\Mahasiswa;

class EdomPengisianResource extends Resource
{
    protected static ?string $model = EdomPengisian::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static ?string $navigationGroup = 'Evaluasi Dosen';

    protected static ?int $navigationSort = 1;

    protected static ?string $pluralModelLabel = 'Evaluasi Dosen';

    protected static ?string $modelLabel = 'Evaluasi Dosen';

    public static function form(Form $form): Form
    {
        $mahasiswaId = Mahasiswa::where('user_id', auth()->id())->first()->id ?? null;

        return $form
            ->schema([
                Forms\Components\Hidden::make('mahasiswa_id')
                    ->default($mahasiswaId),
                Forms\Components\Hidden::make('tanggal_pengisian')
                    ->default(now()->format('Y-m-d')),
                Forms\Components\Hidden::make('status')
                    ->default('draft'),
                Forms\Components\Select::make('jadwal_id')
                    ->label('Periode Evaluasi')
                    ->required()
                    ->options(function () {
                        return EdomJadwal::where('is_aktif', true)
                            ->whereDate('tanggal_mulai', '<=', now())
                            ->whereDate('tanggal_selesai', '>=', now())
                            ->pluck('nama_periode', 'id');
                    })
                    ->searchable()
                    ->preload()
                    ->live()
                    ->afterStateUpdated(fn(callable $set) => $set('jadwal_kuliah_id', null)),
                Forms\Components\Select::make('jadwal_kuliah_id')
                    ->label('Jadwal Mata Kuliah')
                    ->required()
                    ->options(function (callable $get, ?EdomPengisian $record) use ($mahasiswaId) {
                        if (! $get('jadwal_id')) {
                            return [];
                        }

                        // Ambil jadwal kuliah mahasiswa pada semester berjalan
                        $jadwalQuery = \App\Models\Jadwal::query()
                            ->whereHas('krsDetail', function ($query) use ($mahasiswaId) {
                                $query->whereHas('krs', function ($q) use ($mahasiswaId) {
                                    $q->where('mahasiswa_id', $mahasiswaId);
                                });
                            })
                            ->with(['mataKuliah', 'dosen']);

                        // Filter jadwal mata kuliah yang belum dinilai untuk periode evaluasi yang dipilih
                        if ($record) {
                            // Jika edit, exclude jadwal yang sudah dipakai oleh pengisian lain
                            $pengisianIds = EdomPengisian::where('jadwal_id', $get('jadwal_id'))
                                ->where('mahasiswa_id', $mahasiswaId)
                                ->where('id', '!=', $record->id)
                                ->pluck('jadwal_kuliah_id');
                        } else {
                            // Jika create, exclude semua jadwal yang sudah dipakai
                            $pengisianIds = EdomPengisian::where('jadwal_id', $get('jadwal_id'))
                                ->where('mahasiswa_id', $mahasiswaId)
                                ->pluck('jadwal_kuliah_id');
                        }

                        $jadwalQuery->whereNotIn('id', $pengisianIds);

                        // Return options untuk dropdown
                        return $jadwalQuery->get()->mapWithKeys(function ($jadwal) {
                            return [
                                $jadwal->id => "{$jadwal->mataKuliah->nama_mata_kuliah} - {$jadwal->dosen->nama_lengkap}"
                            ];
                        });
                    })
                    ->searchable()
                    ->preload()
                    ->live()
                    ->afterStateUpdated(function (callable $set, callable $get) {
                        $jadwalId = $get('jadwal_kuliah_id');
                        if ($jadwalId) {
                            $jadwal = \App\Models\Jadwal::with(['mataKuliah', 'dosen'])->find($jadwalId);
                            if ($jadwal) {
                                $set('dosen_id', $jadwal->dosen_id);
                                $set('mata_kuliah_id', $jadwal->mata_kuliah_id);
                            }
                        }
                    }),
                Forms\Components\Hidden::make('dosen_id'),
                Forms\Components\Hidden::make('mata_kuliah_id'),
                Forms\Components\Section::make('Formulir Evaluasi')
                    ->description('Berikan evaluasi sesuai dengan pengalaman Anda terhadap dosen dan mata kuliah ini')
                    ->schema(function (callable $get) {
                        if (! $get('jadwal_id')) {
                            return [
                                Forms\Components\Placeholder::make('info')
                                    ->label('Informasi')
                                    ->content('Silakan pilih periode evaluasi dan jadwal mata kuliah terlebih dahulu untuk melihat form evaluasi.')
                            ];
                        }

                        $jadwalId = $get('jadwal_id');
                        $jadwal = EdomJadwal::with('template.pertanyaan')->find($jadwalId);

                        if (! $jadwal) {
                            return [
                                Forms\Components\Placeholder::make('info')
                                    ->label('Informasi')
                                    ->content('Periode evaluasi tidak ditemukan.')
                            ];
                        }

                        $pertanyaan = $jadwal->template->pertanyaan()->orderBy('urutan')->get();

                        if ($pertanyaan->isEmpty()) {
                            return [
                                Forms\Components\Placeholder::make('info')
                                    ->label('Informasi')
                                    ->content('Belum ada pertanyaan evaluasi untuk periode ini.')
                            ];
                        }

                        $fields = [];

                        // Informasi header
                        $fields[] = Forms\Components\Placeholder::make('header_info')
                            ->label('Petunjuk Pengisian')
                            ->content('Untuk pertanyaan skala: 1 = Sangat Tidak Setuju, 2 = Tidak Setuju, 3 = Netral, 4 = Setuju, 5 = Sangat Setuju')
                            ->columnSpanFull();

                        // Buat fields berdasarkan pertanyaan
                        foreach ($pertanyaan as $item) {
                            if ($item->jenis === 'likert_scale') {
                                $fields[] = Forms\Components\Radio::make("jawaban.{$item->id}.nilai")
                                    ->label($item->pertanyaan)
                                    ->options([
                                        1 => '1 - Sangat Tidak Setuju',
                                        2 => '2 - Tidak Setuju',
                                        3 => '3 - Netral',
                                        4 => '4 - Setuju',
                                        5 => '5 - Sangat Setuju',
                                    ])
                                    ->required($item->is_required)
                                    ->columnSpanFull();
                            } else {
                                $fields[] = Forms\Components\Textarea::make("jawaban.{$item->id}.jawaban_text")
                                    ->label($item->pertanyaan)
                                    ->required($item->is_required)
                                    ->columnSpanFull();
                            }
                        }

                        return $fields;
                    }),
            ]);
    }

    public static function table(Table $table): Table
    {
        $mahasiswaId = Mahasiswa::where('user_id', auth()->id())->first()->id ?? null;

        return $table
            ->modifyQueryUsing(function (Builder $query) use ($mahasiswaId) {
                $query->where('mahasiswa_id', $mahasiswaId);
            })
            ->columns([
                Tables\Columns\TextColumn::make('jadwal.nama_periode')
                    ->label('Periode Evaluasi')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('jadwal.tahunAkademik.nama_lengkap')
                    ->label('Tahun Akademik')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('mataKuliah.nama_mata_kuliah')
                    ->label('Mata Kuliah')
                    ->searchable(),
                Tables\Columns\TextColumn::make('dosen.nama_lengkap')
                    ->label('Dosen')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tanggal_pengisian')
                    ->label('Tanggal Pengisian')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
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
                Tables\Filters\SelectFilter::make('jadwal_id')
                    ->label('Periode Evaluasi')
                    ->relationship('jadwal', 'nama_periode')
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'draft' => 'Draft',
                        'submitted' => 'Sudah Disubmit',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->visible(fn(EdomPengisian $record): bool => $record->status === 'draft'),
                Tables\Actions\Action::make('submit')
                    ->label('Submit Evaluasi')
                    ->icon('heroicon-m-check-circle')
                    ->color('success')
                    ->visible(fn(EdomPengisian $record): bool => $record->status === 'draft')
                    ->action(function (EdomPengisian $record): void {
                        $record->status = 'submitted';
                        $record->save();
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Submit Evaluasi')
                    ->modalDescription('Setelah di-submit, evaluasi tidak dapat diubah lagi. Apakah Anda yakin ingin melanjutkan?')
                    ->modalSubmitActionLabel('Ya, Submit Sekarang')
                    ->successNotificationTitle('Evaluasi berhasil disubmit'),
                Tables\Actions\EditAction::make('view')
                    ->label('Lihat Detail')
                    ->visible(fn(EdomPengisian $record): bool => $record->status === 'submitted'),
            ])
            ->bulkActions([
                // Tidak ada bulk actions
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEdomPengisians::route('/'),
            'create' => Pages\CreateEdomPengisian::route('/create'),
            'edit' => Pages\EditEdomPengisian::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $mahasiswaId = Mahasiswa::where('user_id', auth()->id())->first()->id ?? null;
        return parent::getEloquentQuery()->where('mahasiswa_id', $mahasiswaId);
    }

    // Menyimpan data jawaban
    public static function saveJawaban(EdomPengisian $pengisian, array $jawaban): void
    {
        // Hapus jawaban yang sudah ada (jika update)
        EdomPengisianDetail::where('pengisian_id', $pengisian->id)->delete();

        foreach ($jawaban as $pertanyaanId => $data) {
            // Cek apakah pertanyaan ada
            $pertanyaan = EdomPertanyaan::find($pertanyaanId);
            if (!$pertanyaan) {
                continue;
            }

            // Create jawaban baru
            EdomPengisianDetail::create([
                'pengisian_id' => $pengisian->id,
                'pertanyaan_id' => $pertanyaanId,
                'nilai' => $data['nilai'] ?? null,
                'jawaban_text' => $data['jawaban_text'] ?? null,
            ]);
        }
    }
}
