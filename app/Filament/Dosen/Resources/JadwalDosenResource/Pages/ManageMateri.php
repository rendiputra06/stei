<?php

namespace App\Filament\Dosen\Resources\JadwalDosenResource\Pages;

use App\Filament\Dosen\Resources\JadwalDosenResource;
use App\Models\Jadwal;
use App\Models\MateriPerkuliahan;
use App\Models\Presensi;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action as TableAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;

class ManageMateri extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string $resource = JadwalDosenResource::class;

    protected static string $view = 'filament.dosen.resources.jadwal-dosen-resource.pages.manage-materi';

    public Jadwal $record;

    public function mount(Jadwal $record): void
    {
        $this->record = $record;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                MateriPerkuliahan::query()
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
                TextColumn::make('judul')
                    ->label('Judul Materi')
                    ->searchable()
                    ->limit(50),
                IconColumn::make('hasFile')
                    ->label('File')
                    ->boolean()
                    ->getStateUsing(fn($record) => $record->hasFile()),
                TextColumn::make('deskripsi')
                    ->label('Deskripsi')
                    ->limit(50),
                TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->actions([
                TableAction::make('download')
                    ->label('Download')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->url(fn(MateriPerkuliahan $record) => $record->hasFile() ? $record->getFileUrl() : null)
                    ->openUrlInNewTab()
                    ->visible(fn(MateriPerkuliahan $record) => $record->hasFile()),
                TableAction::make('edit')
                    ->icon('heroicon-o-pencil')
                    ->form([
                        Section::make('Informasi Materi')
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
                                TextInput::make('judul')
                                    ->label('Judul Materi')
                                    ->required()
                                    ->maxLength(255),
                                Textarea::make('deskripsi')
                                    ->label('Deskripsi')
                                    ->rows(3),
                                FileUpload::make('file_path')
                                    ->label('File Materi')
                                    ->disk('public')
                                    ->directory('materi')
                                    ->visibility('public')
                                    ->acceptedFileTypes(['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.ms-powerpoint', 'application/vnd.openxmlformats-officedocument.presentationml.presentation'])
                                    ->maxSize(5120) // 5MB
                            ])->columns(2),
                    ])
                    ->action(function (MateriPerkuliahan $record, array $data) {
                        // Jika ada file baru dan file lama ada, hapus file lama
                        if (isset($data['file_path']) && $record->file_path) {
                            Storage::disk('public')->delete($record->file_path);
                        }

                        // Jika tidak ada file baru, jangan update file_path
                        if (!isset($data['file_path'])) {
                            unset($data['file_path']);
                        }

                        $record->update($data);
                    }),
                TableAction::make('delete')
                    ->label('Hapus')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(function (MateriPerkuliahan $record) {
                        // Hapus file jika ada
                        if ($record->file_path) {
                            Storage::disk('public')->delete($record->file_path);
                        }

                        $record->delete();
                    }),
            ])
            ->headerActions([
                TableAction::make('create')
                    ->label('Tambah Materi')
                    ->icon('heroicon-o-plus')
                    ->form([
                        Section::make('Informasi Materi')
                            ->schema([
                                Select::make('pertemuan_ke')
                                    ->label('Pertemuan')
                                    ->required()
                                    ->options(function () {
                                        // Daftar pertemuan dari presensi
                                        $pertemuans = Presensi::where('jadwal_id', $this->record->id)
                                            ->orderBy('pertemuan_ke')
                                            ->pluck('pertemuan_ke', 'pertemuan_ke')
                                            ->toArray();

                                        // Jika tidak ada presensi, tampilkan 1-16
                                        if (empty($pertemuans)) {
                                            $pertemuans = array_combine(range(1, 16), range(1, 16));
                                        }

                                        return $pertemuans;
                                    }),
                                DatePicker::make('tanggal')
                                    ->label('Tanggal')
                                    ->required()
                                    ->default(now()),
                                TextInput::make('judul')
                                    ->label('Judul Materi')
                                    ->required()
                                    ->maxLength(255),
                                Textarea::make('deskripsi')
                                    ->label('Deskripsi')
                                    ->rows(3),
                                FileUpload::make('file_path')
                                    ->label('File Materi')
                                    ->disk('public')
                                    ->directory('materi')
                                    ->visibility('public')
                                    ->acceptedFileTypes(['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.ms-powerpoint', 'application/vnd.openxmlformats-officedocument.presentationml.presentation'])
                                    ->maxSize(5120) // 5MB
                            ])->columns(2),
                    ])
                    ->action(function (array $data) {
                        // Buat materi baru
                        MateriPerkuliahan::create([
                            'jadwal_id' => $this->record->id,
                            'pertemuan_ke' => $data['pertemuan_ke'],
                            'tanggal' => $data['tanggal'],
                            'judul' => $data['judul'],
                            'deskripsi' => $data['deskripsi'],
                            'file_path' => $data['file_path'] ?? null,
                        ]);
                    }),
            ]);
    }
}
