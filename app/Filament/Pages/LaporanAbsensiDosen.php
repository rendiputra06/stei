<?php

namespace App\Filament\Pages;

use App\Models\AbsensiDosen;
use App\Models\Dosen;
use App\Models\TahunAkademik;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class LaporanAbsensiDosen extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static ?string $navigationLabel = 'Laporan Absensi Dosen';

    protected static ?string $title = 'Laporan Absensi Dosen';

    protected static ?int $navigationSort = 20;

    protected static string $view = 'filament.pages.laporan-absensi-dosen';

    public function table(Table $table): Table
    {
        return $table
            ->query(AbsensiDosen::query())
            ->columns([
                Tables\Columns\TextColumn::make('dosen.nama')
                    ->label('Dosen')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('jadwal.mataKuliah.nama')
                    ->label('Mata Kuliah')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('jadwal.hari')
                    ->label('Hari')
                    ->sortable(),
                Tables\Columns\TextColumn::make('jadwal.jam_mulai')
                    ->label('Jadwal')
                    ->formatStateUsing(fn($record) => $record->jadwal->jam_mulai->format('H:i') . ' - ' . $record->jadwal->jam_selesai->format('H:i'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('tanggal')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('jam_masuk')
                    ->dateTime('H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('jam_keluar')
                    ->dateTime('H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'hadir' => 'success',
                        'izin' => 'warning',
                        'sakit' => 'info',
                        'alpha' => 'danger',
                        default => 'gray'
                    })
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'hadir' => 'Hadir',
                        'izin' => 'Izin',
                        'sakit' => 'Sakit',
                        'alpha' => 'Tanpa Keterangan',
                        default => $state,
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('keterangan')
                    ->limit(30)
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('dosen_id')
                    ->label('Dosen')
                    ->relationship('dosen', 'nama')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('status')
                    ->label('Status')
                    ->options(AbsensiDosen::getStatusList()),
                Filter::make('tanggal')
                    ->form([
                        Forms\Components\DatePicker::make('dari_tanggal')
                            ->label('Dari Tanggal'),
                        Forms\Components\DatePicker::make('sampai_tanggal')
                            ->label('Sampai Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['dari_tanggal'],
                                fn(Builder $query, $date): Builder => $query->whereDate('tanggal', '>=', $date),
                            )
                            ->when(
                                $data['sampai_tanggal'],
                                fn(Builder $query, $date): Builder => $query->whereDate('tanggal', '<=', $date),
                            );
                    }),
                Filter::make('tahun_akademik')
                    ->form([
                        Forms\Components\Select::make('tahun_akademik_id')
                            ->label('Tahun Akademik')
                            ->options(
                                TahunAkademik::orderBy('tahun', 'desc')
                                    ->orderBy('semester', 'desc')
                                    ->get()
                                    ->pluck('nama', 'id')
                            ),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['tahun_akademik_id'],
                                fn(Builder $query, $tahunAkademikId): Builder => $query->whereHas('jadwal', fn($q) => $q->where('tahun_akademik_id', $tahunAkademikId))
                            );
                    }),
            ])
            ->filtersLayout(FiltersLayout::AboveContent)
            ->actions([
                // Menghapus action Edit
            ])
            ->bulkActions([
                // Menghapus bulk actions
            ])
            ->emptyStateHeading('Belum ada data absensi dosen')
            ->emptyStateDescription('Data absensi akan muncul saat dosen melakukan absensi menggunakan QR Code.')
            ->emptyStateIcon('heroicon-o-clipboard-document-check');
    }
}
