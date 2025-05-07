<?php

namespace App\Filament\Mahasiswa\Resources;

use App\Filament\Mahasiswa\Resources\KRSResource\Pages;
use App\Filament\Mahasiswa\Resources\KRSResource\RelationManagers;
use App\Models\KRS;
use App\Models\TahunAkademik;
use App\Models\Mahasiswa;
use App\Models\StatusMahasiswa;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Filament\Notifications\Notification;
use Filament\Notifications\Actions\Action as NotificationAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class KRSResource extends Resource
{
    protected static ?string $model = KRS::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'Pengisian KRS';

    protected static ?string $navigationGroup = 'Akademik';

    protected static ?int $navigationSort = 10;

    protected static ?string $slug = 'pengisian-krs';

    public static function shouldRegisterNavigation(): bool
    {
        // Hanya tampilkan di menu navigasi jika ada tahun akademik aktif
        return TahunAkademik::getAktif() !== null;
    }

    public static function form(Form $form): Form
    {
        $mahasiswa = Mahasiswa::where('user_id', Auth::id())->first();
        $tahunAkademik = TahunAkademik::getAktif();

        return $form
            ->schema([
                Forms\Components\Section::make('Informasi KRS')
                    ->schema([
                        Forms\Components\TextInput::make('mahasiswa.nama')
                            ->label('Nama Mahasiswa')
                            ->disabled()
                            ->dehydrated(false)
                            ->default(function () use ($mahasiswa) {
                                return $mahasiswa ? $mahasiswa->nama : '';
                            }),
                        Forms\Components\TextInput::make('mahasiswa.nim')
                            ->label('NIM')
                            ->disabled()
                            ->dehydrated(false)
                            ->default(function () use ($mahasiswa) {
                                return $mahasiswa ? $mahasiswa->nim : '';
                            }),
                        Forms\Components\TextInput::make('semester')
                            ->label('Semester')
                            ->numeric()
                            ->disabled()
                            ->default(function () use ($mahasiswa, $tahunAkademik) {
                                if ($mahasiswa && $tahunAkademik) {
                                    return StatusMahasiswa::hitungSemester($mahasiswa, $tahunAkademik);
                                }
                                return 0;
                            }),
                        Forms\Components\Select::make('tahun_akademik_id')
                            ->label('Tahun Akademik')
                            ->relationship('tahunAkademik', 'nama')
                            ->default(function () use ($tahunAkademik) {
                                return $tahunAkademik ? $tahunAkademik->id : null;
                            })
                            ->disabled()
                            ->required(),
                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options(KRS::getStatusList())
                            ->disabled()
                            ->default('draft')
                            ->required(),
                        Forms\Components\Textarea::make('keterangan')
                            ->label('Keterangan')
                            ->placeholder('Tambahkan catatan jika diperlukan')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
                Forms\Components\Section::make('Informasi Pengisian')
                    ->schema([
                        Forms\Components\Placeholder::make('created_at')
                            ->label('Dibuat Pada')
                            ->content(fn(KRS $record): ?string => $record->created_at?->format('d M Y H:i')),
                        Forms\Components\Placeholder::make('tanggal_submit')
                            ->label('Diajukan Pada')
                            ->content(fn(KRS $record): ?string => $record->tanggal_submit?->format('d M Y H:i') ?? '-'),
                        Forms\Components\Placeholder::make('tanggal_approval')
                            ->label('Disetujui/Ditolak Pada')
                            ->content(fn(KRS $record): ?string => $record->tanggal_approval?->format('d M Y H:i') ?? '-'),
                        Forms\Components\Placeholder::make('approved_by')
                            ->label('Disetujui/Ditolak Oleh')
                            ->content(fn(KRS $record): ?string => $record->approvedBy?->name ?? '-'),
                        Forms\Components\Placeholder::make('total_sks')
                            ->label('Total SKS')
                            ->content(fn(KRS $record): int => $record->total_sks),
                    ])
                    ->columns(2)
                    ->visible(fn($livewire) => $livewire instanceof Pages\EditKRS),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tahunAkademik.nama')
                    ->label('Tahun Akademik')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('semester')
                    ->label('Semester')
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_sks')
                    ->label('Total SKS')
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'primary' => 'draft',
                        'warning' => 'submitted',
                        'success' => 'approved',
                        'danger' => 'rejected',
                    ])
                    ->formatStateUsing(fn($state) => KRS::getStatusList()[$state] ?? $state),
                Tables\Columns\TextColumn::make('tanggal_submit')
                    ->label('Tanggal Submit')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diperbarui Pada')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options(KRS::getStatusList()),
                Tables\Filters\SelectFilter::make('tahun_akademik_id')
                    ->label('Tahun Akademik')
                    ->relationship('tahunAkademik', 'nama'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make()
                    ->visible(fn(KRS $record): bool => $record->isDraftStatus() && static::dapatMengisiKRS()),
                Action::make('submit')
                    ->label('Ajukan KRS')
                    ->icon('heroicon-o-paper-airplane')
                    ->color('success')
                    ->visible(fn(KRS $record): bool => $record->isDraftStatus() && static::dapatMengisiKRS())
                    ->requiresConfirmation()
                    ->modalHeading('Ajukan KRS')
                    ->modalDescription('Anda yakin ingin mengajukan KRS ini? Setelah diajukan, Anda tidak dapat mengubah KRS lagi.')
                    ->action(function (KRS $record) {
                        // Cek jumlah SKS minimal
                        if ($record->total_sks < 12) {
                            Notification::make()
                                ->title('Total SKS minimal adalah 12')
                                ->body('Silakan tambahkan lebih banyak mata kuliah sebelum mengajukan KRS.')
                                ->danger()
                                ->send();
                            return;
                        }

                        // Cek jumlah SKS maksimal
                        if ($record->total_sks > 24) {
                            Notification::make()
                                ->title('Total SKS maksimal adalah 24')
                                ->body('Silakan kurangi jumlah mata kuliah sebelum mengajukan KRS.')
                                ->danger()
                                ->send();
                            return;
                        }

                        // Update status menjadi submitted
                        $record->status = 'submitted';
                        $record->tanggal_submit = now();
                        $record->save();

                        Notification::make()
                            ->title('KRS berhasil diajukan')
                            ->body('KRS anda telah berhasil diajukan dan menunggu persetujuan dari dosen wali.')
                            ->success()
                            ->send();
                    }),
            ])
            ->bulkActions([
                // Tidak ada bulk action yang diperlukan
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()
                    ->visible(fn(): bool => static::dapatMembuatKRS()),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\KRSDetailRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKRS::route('/'),
            'create' => Pages\CreateKRS::route('/create'),
            'view' => Pages\ViewKRS::route('/{record}'),
            'edit' => Pages\EditKRS::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        // Hanya tampilkan KRS milik mahasiswa yang login
        $mahasiswa = Mahasiswa::where('user_id', Auth::id())->first();

        if (!$mahasiswa) {
            return parent::getEloquentQuery()->where('id', 0);
        }

        return parent::getEloquentQuery()
            ->where('mahasiswa_id', $mahasiswa->id);
    }

    /**
     * Check apakah mahasiswa dapat membuat KRS baru
     */
    public static function dapatMembuatKRS(): bool
    {
        $mahasiswa = Mahasiswa::where('user_id', Auth::id())->first();
        $tahunAkademik = TahunAkademik::getAktif();

        // Cek jika mahasiswa ditemukan
        if (!$mahasiswa) {
            return false;
        }

        // Cek jika tahun akademik aktif
        if (!$tahunAkademik) {
            return false;
        }

        // Cek jika KRS sudah ada
        $krsExists = KRS::where('mahasiswa_id', $mahasiswa->id)
            ->where('tahun_akademik_id', $tahunAkademik->id)
            ->exists();

        if ($krsExists) {
            return false;
        }

        // Cek status mahasiswa
        $statusMahasiswa = StatusMahasiswa::where('mahasiswa_id', $mahasiswa->id)
            ->where('tahun_akademik_id', $tahunAkademik->id)
            ->first();

        if (!$statusMahasiswa || $statusMahasiswa->status !== 'aktif') {
            return false;
        }

        // Cek jika saat ini adalah periode pengisian KRS
        return static::dapatMengisiKRS();
    }

    /**
     * Check apakah saat ini adalah periode pengisian KRS
     */
    public static function dapatMengisiKRS(): bool
    {
        $tahunAkademik = TahunAkademik::getAktif();

        // Cek jika tahun akademik aktif
        if (!$tahunAkademik) {
            return false;
        }

        // Cek jika saat ini adalah periode pengisian KRS
        $now = Carbon::now();
        return $now->between($tahunAkademik->krs_mulai, $tahunAkademik->krs_selesai);
    }
}
