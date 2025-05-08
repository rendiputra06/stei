<?php

namespace App\Filament\Dosen\Resources\JadwalDosenResource\Pages;

use App\Filament\Dosen\Resources\JadwalDosenResource;
use App\Models\Jadwal;
use App\Models\Presensi;
use App\Models\PresensiDetail;
use App\Models\KRSDetail;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Form;
use Filament\Resources\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action as TableAction;
use Illuminate\Database\Eloquent\Builder;

class ManagePresensi extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string $resource = JadwalDosenResource::class;

    protected static string $view = 'filament.dosen.resources.jadwal-dosen-resource.pages.manage-presensi';

    public Jadwal $record;

    public function mount(Jadwal $record): void
    {
        $this->record = $record;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Presensi::query()
                    ->where('jadwal_id', $this->record->id)
                    ->orderBy('pertemuan_ke')
            )
            ->columns([
                TextColumn::make('pertemuan_ke')
                    ->label('Pertemuan')
                    ->sortable(),
                TextColumn::make('tanggal')
                    ->date('d M Y')
                    ->sortable(),
                TextColumn::make('mahasiswaHadir')
                    ->label('Hadir')
                    ->getStateUsing(fn($record) => $record->mahasiswaHadir()),
                TextColumn::make('mahasiswaTidakHadir')
                    ->label('Tidak Hadir')
                    ->getStateUsing(fn($record) => $record->mahasiswaTidakHadir()),
                TextColumn::make('totalMahasiswa')
                    ->label('Total')
                    ->getStateUsing(fn($record) => $record->totalMahasiswa()),
                TextColumn::make('persentaseKehadiran')
                    ->label('Kehadiran (%)')
                    ->getStateUsing(fn($record) => number_format($record->persentaseKehadiran(), 2) . '%'),
                TextColumn::make('keterangan')
                    ->label('Keterangan')
                    ->limit(50),
            ])
            ->actions([
                TableAction::make('detail')
                    ->label('Detail')
                    ->icon('heroicon-o-clipboard-document-check')
                    ->modalHeading(fn(Presensi $record) => 'Detail Presensi Pertemuan ' . $record->pertemuan_ke)
                    ->modalDescription(fn(Presensi $record) => 'Tanggal: ' . $record->tanggal->format('d M Y'))
                    ->modalWidth('7xl')
                    ->modalContent(function (Presensi $record) {
                        $details = $record->presensiDetail()->with('mahasiswa')->get();

                        return view('filament.dosen.resources.jadwal-dosen-resource.partials.presensi-detail', [
                            'presensi' => $record,
                            'details' => $details,
                        ]);
                    }),
                TableAction::make('edit')
                    ->icon('heroicon-o-pencil')
                    ->form([
                        Section::make('Informasi Presensi')
                            ->schema([
                                TextInput::make('pertemuan_ke')
                                    ->label('Pertemuan')
                                    ->required()
                                    ->numeric()
                                    ->minValue(1)
                                    ->maxValue(16)
                                    ->disabled(),
                                DatePicker::make('tanggal')
                                    ->label('Tanggal')
                                    ->required(),
                                Textarea::make('keterangan')
                                    ->label('Keterangan')
                                    ->rows(3),
                            ])->columns(2),
                    ])
                    ->action(function (Presensi $record, array $data) {
                        $record->update($data);
                    }),
                TableAction::make('kelola_presensi')
                    ->label('Kelola Kehadiran')
                    ->icon('heroicon-o-user-group')
                    ->modalHeading(fn(Presensi $record) => 'Kelola Kehadiran Pertemuan ' . $record->pertemuan_ke)
                    ->modalDescription(fn(Presensi $record) => 'Tanggal: ' . $record->tanggal->format('d M Y'))
                    ->modalWidth('7xl')
                    ->form(function (Presensi $record) {
                        // Dapatkan mahasiswa yang terdaftar di kelas ini
                        $mahasiswas = KRSDetail::where('jadwal_id', $record->jadwal_id)
                            ->where('status', 'aktif')
                            ->with(['krs.mahasiswa'])
                            ->get()
                            ->map(function ($krsDetail) {
                                return [
                                    'id' => $krsDetail->krs->mahasiswa->id,
                                    'nama' => $krsDetail->krs->mahasiswa->nama,
                                    'nim' => $krsDetail->krs->mahasiswa->nim,
                                ];
                            });

                        // Dapatkan presensi detail yang sudah ada
                        $existingPresensi = PresensiDetail::where('presensi_id', $record->id)
                            ->get()
                            ->keyBy('mahasiswa_id');

                        return [
                            Repeater::make('presensi_mahasiswa')
                                ->schema([
                                    TextInput::make('nama_mahasiswa')
                                        ->label('Nama Mahasiswa')
                                        ->disabled(),
                                    TextInput::make('nim')
                                        ->label('NIM')
                                        ->disabled(),
                                    Select::make('status')
                                        ->label('Status')
                                        ->options(PresensiDetail::getStatusList())
                                        ->required(),
                                    Textarea::make('keterangan')
                                        ->label('Keterangan')
                                        ->rows(1),
                                    TextInput::make('mahasiswa_id')
                                        ->hidden(),
                                ])
                                ->columns(4)
                                ->itemLabel(fn(array $state) => $state['nama_mahasiswa'] ?? 'Mahasiswa')
                                ->defaultItems(count($mahasiswas))
                                ->disableItemCreation()
                                ->disableItemDeletion()
                                ->disableItemMovement()
                                ->hiddenLabel()
                                ->afterStateHydrated(function (Repeater $component, $state) use ($mahasiswas, $existingPresensi) {
                                    $items = [];

                                    foreach ($mahasiswas as $index => $mahasiswa) {
                                        $presensi = $existingPresensi[$mahasiswa['id']] ?? null;

                                        $items[] = [
                                            'nama_mahasiswa' => $mahasiswa['nama'],
                                            'nim' => $mahasiswa['nim'],
                                            'status' => $presensi ? $presensi->status : 'hadir',
                                            'keterangan' => $presensi ? $presensi->keterangan : null,
                                            'mahasiswa_id' => $mahasiswa['id'],
                                        ];
                                    }

                                    $component->state($items);
                                }),
                        ];
                    })
                    ->action(function (Presensi $record, array $data) {
                        foreach ($data['presensi_mahasiswa'] as $item) {
                            PresensiDetail::updateOrCreate(
                                [
                                    'presensi_id' => $record->id,
                                    'mahasiswa_id' => $item['mahasiswa_id'],
                                ],
                                [
                                    'status' => $item['status'],
                                    'keterangan' => $item['keterangan'],
                                ]
                            );
                        }
                    }),
            ])
            ->headerActions([
                TableAction::make('create')
                    ->label('Tambah Presensi')
                    ->icon('heroicon-o-plus')
                    ->form([
                        Section::make('Informasi Presensi')
                            ->schema([
                                TextInput::make('pertemuan_ke')
                                    ->label('Pertemuan')
                                    ->required()
                                    ->numeric()
                                    ->minValue(1)
                                    ->maxValue(16),
                                DatePicker::make('tanggal')
                                    ->label('Tanggal')
                                    ->required()
                                    ->default(now()),
                                Textarea::make('keterangan')
                                    ->label('Keterangan')
                                    ->rows(3),
                            ])->columns(2),
                    ])
                    ->action(function (array $data) {
                        // Cek apakah pertemuan sudah ada
                        $exists = Presensi::where('jadwal_id', $this->record->id)
                            ->where('pertemuan_ke', $data['pertemuan_ke'])
                            ->exists();

                        if ($exists) {
                            $this->addError('pertemuan_ke', 'Pertemuan ini sudah ada');
                            return;
                        }

                        // Buat presensi baru
                        $presensi = Presensi::create([
                            'jadwal_id' => $this->record->id,
                            'pertemuan_ke' => $data['pertemuan_ke'],
                            'tanggal' => $data['tanggal'],
                            'keterangan' => $data['keterangan'],
                        ]);

                        // Buat presensi detail untuk semua mahasiswa di kelas ini
                        $krsDetails = KRSDetail::where('jadwal_id', $this->record->id)
                            ->where('status', 'aktif')
                            ->with(['krs.mahasiswa'])
                            ->get();

                        foreach ($krsDetails as $krsDetail) {
                            PresensiDetail::create([
                                'presensi_id' => $presensi->id,
                                'mahasiswa_id' => $krsDetail->krs->mahasiswa_id,
                                'status' => 'hadir', // Default hadir
                                'keterangan' => null,
                            ]);
                        }
                    }),
            ]);
    }
}
