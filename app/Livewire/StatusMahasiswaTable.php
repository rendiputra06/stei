<?php

namespace App\Livewire;

use App\Models\Mahasiswa;
use App\Models\StatusMahasiswa;
use App\Models\TahunAkademik;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;

class StatusMahasiswaTable extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        return $table
            ->query(StatusMahasiswa::query()->with(['mahasiswa', 'mahasiswa.programStudi', 'tahunAkademik']))
            ->emptyStateHeading('Tidak Ada Data')
            ->emptyStateDescription('Tidak ada data status mahasiswa yang tersedia. Harap pilih tahun akademik terlebih dahulu untuk menampilkan data.')
            ->defaultGroup('mahasiswa.program_studi_id')
            ->defaultSort('mahasiswa.nama')
            ->columns([
                TextColumn::make('mahasiswa.nim')
                    ->label('NIM')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('mahasiswa.nama')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('mahasiswa.programStudi.nama')
                    ->label('Program Studi')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('tahunAkademik.nama')
                    ->label('Tahun Akademik')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('semester')
                    ->label('Semester')
                    ->sortable(),

                BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'secondary' => 'tidak_aktif',
                        'success' => 'aktif',
                        'warning' => 'cuti',
                        'primary' => 'lulus',
                        'danger' => 'drop_out',
                    ])
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'tidak_aktif' => 'Tidak Aktif',
                        'aktif' => 'Aktif',
                        'cuti' => 'Cuti',
                        'lulus' => 'Lulus',
                        'drop_out' => 'Drop Out',
                        default => $state,
                    }),

                TextColumn::make('ip_semester')
                    ->label('IP Semester')
                    ->numeric(2)
                    ->sortable(),

                TextColumn::make('ipk')
                    ->label('IPK')
                    ->numeric(2)
                    ->sortable(),

                TextColumn::make('sks_semester')
                    ->label('SKS Semester')
                    ->sortable(),

                TextColumn::make('sks_total')
                    ->label('Total SKS')
                    ->sortable(),

                TextColumn::make('keterangan')
                    ->label('Keterangan')
                    ->limit(30)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();

                        if (strlen($state) <= $column->getLimit()) {
                            return null;
                        }

                        return $state;
                    }),
            ])
            ->filters([
                SelectFilter::make('tahun_akademik_id')
                    ->label('Tahun Akademik')
                    ->relationship('tahunAkademik', 'nama')
                    ->default(function () {
                        $tahunAkademikAktif = TahunAkademik::getAktif();
                        return $tahunAkademikAktif ? $tahunAkademikAktif->id : null;
                    })
                    ->required()
                    ->preload(),

                SelectFilter::make('status')
                    ->label('Status')
                    ->options(StatusMahasiswa::getStatusList()),

                SelectFilter::make('program_studi_id')
                    ->label('Program Studi')
                    ->relationship('mahasiswa.programStudi', 'nama')
                    ->preload(),

                SelectFilter::make('semester')
                    ->label('Semester')
                    ->options(array_combine(range(1, 8), range(1, 8))),
            ], layout: FiltersLayout::AboveContent)
            ->filtersFormColumns(3);
    }

    public function render()
    {
        return view('livewire.status-mahasiswa-table');
    }
}
