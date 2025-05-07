<?php

namespace App\Filament\Dosen\Resources;

use App\Filament\Dosen\Resources\KRSResource\Pages;
use App\Filament\Dosen\Resources\KRSResource\RelationManagers;
use App\Models\KRS;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\TahunAkademik;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class KRSResource extends Resource
{
    protected static ?string $model = KRS::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $navigationLabel = 'Persetujuan KRS';

    protected static ?string $navigationGroup = 'Akademik';

    protected static ?int $navigationSort = 10;

    protected static ?string $slug = 'persetujuan-krs';

    public static function shouldRegisterNavigation(): bool
    {
        // Hanya tampilkan di menu navigasi jika dosen memiliki mahasiswa bimbingan
        $dosen = Dosen::where('user_id', Auth::id())->first();
        return $dosen && $dosen->mahasiswaBimbingan()->count() > 0;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi KRS')
                    ->schema([
                        Forms\Components\TextInput::make('mahasiswa.nama')
                            ->label('Nama Mahasiswa')
                            ->disabled(),
                        Forms\Components\TextInput::make('mahasiswa.nim')
                            ->label('NIM')
                            ->disabled(),
                        Forms\Components\TextInput::make('mahasiswa.programStudi.nama')
                            ->label('Program Studi')
                            ->disabled(),
                        Forms\Components\TextInput::make('semester')
                            ->label('Semester')
                            ->numeric()
                            ->disabled(),
                        Forms\Components\Select::make('tahun_akademik_id')
                            ->label('Tahun Akademik')
                            ->relationship('tahunAkademik', 'nama')
                            ->disabled(),
                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options(KRS::getStatusList())
                            ->disabled(),
                        Forms\Components\Textarea::make('keterangan')
                            ->label('Keterangan dari Mahasiswa')
                            ->disabled()
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Persetujuan KRS')
                    ->schema([
                        Forms\Components\Textarea::make('catatan_dosen')
                            ->label('Catatan Dosen')
                            ->placeholder('Tambahkan catatan untuk mahasiswa jika diperlukan')
                            ->columnSpanFull(),
                    ]),

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
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('mahasiswa.nama')
                    ->label('Nama Mahasiswa')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('mahasiswa.nim')
                    ->label('NIM')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('mahasiswa.programStudi.nama')
                    ->label('Program Studi')
                    ->sortable(),
                Tables\Columns\TextColumn::make('tahunAkademik.nama')
                    ->label('Tahun Akademik')
                    ->sortable(),
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
                    ->visible(fn(KRS $record): bool => $record->isSubmitted()),
                Action::make('approve')
                    ->label('Setujui')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn(KRS $record): bool => $record->isSubmitted())
                    ->requiresConfirmation()
                    ->modalHeading('Setujui KRS')
                    ->modalDescription('Anda yakin ingin menyetujui KRS ini?')
                    ->form([
                        Forms\Components\Textarea::make('catatan')
                            ->label('Catatan untuk Mahasiswa (opsional)')
                            ->placeholder('Masukkan catatan jika diperlukan'),
                    ])
                    ->action(function (KRS $record, array $data) {
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
                Action::make('reject')
                    ->label('Tolak')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn(KRS $record): bool => $record->isSubmitted())
                    ->requiresConfirmation()
                    ->modalHeading('Tolak KRS')
                    ->modalDescription('Anda yakin ingin menolak KRS ini?')
                    ->form([
                        Forms\Components\Textarea::make('catatan')
                            ->label('Alasan Penolakan / Catatan')
                            ->placeholder('Masukkan alasan penolakan')
                            ->required(),
                    ])
                    ->action(function (KRS $record, array $data) {
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
                Action::make('print')
                    ->label('Cetak KRS')
                    ->icon('heroicon-o-printer')
                    ->url(fn(KRS $record): string => route('krs.print', ['krs' => $record]))
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([
                // Tidak ada bulk action yang diperlukan
            ])
            ->defaultSort('tanggal_submit', 'desc')
            ->emptyStateHeading('Belum ada KRS yang Diajukan')
            ->emptyStateDescription('KRS yang diajukan oleh mahasiswa bimbingan Anda akan muncul di sini.');
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
        // Ambil dosen berdasarkan user yang login
        $dosen = Dosen::where('user_id', Auth::id())->first();

        if (!$dosen) {
            return parent::getEloquentQuery()->where('id', 0);
        }

        // Ambil mahasiswa bimbingan dosen
        $mahasiswaBimbinganIds = $dosen->mahasiswaBimbingan()->pluck('mahasiswa.id')->toArray();

        // Tampilkan hanya KRS dari mahasiswa bimbingan dosen
        return parent::getEloquentQuery()
            ->whereIn('mahasiswa_id', $mahasiswaBimbinganIds);
    }
}
