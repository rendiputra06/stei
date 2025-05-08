<?php

namespace App\Filament\Dosen\Widgets;

use App\Models\Jadwal;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\Auth;

class JadwalMengajarWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Jadwal::query()
                    ->where('dosen_id', Auth::user()->dosen->id)
                    ->where('is_active', true)
                    ->orderBy('hari')
            )
            ->heading('Jadwal Mengajar Saat Ini')
            ->columns([
                Tables\Columns\TextColumn::make('mataKuliah.nama')
                    ->label('Mata Kuliah')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('hari')
                    ->sortable(),
                Tables\Columns\TextColumn::make('jam_mulai')
                    ->label('Jam')
                    ->formatStateUsing(fn($state, $record) => $state->format('H:i') . ' - ' . $record->jam_selesai->format('H:i')),
                Tables\Columns\TextColumn::make('ruangan.nama')
                    ->label('Ruangan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('kelas')
                    ->searchable(),
                Tables\Columns\TextColumn::make('jumlahMahasiswa')
                    ->label('Jumlah Mahasiswa')
                    ->getStateUsing(fn($record) => $record->jumlahMahasiswa()),
            ])
            ->actions([
                Tables\Actions\Action::make('lihat')
                    ->url(fn(Jadwal $record): string => route('filament.dosen.resources.jadwal-dosens.view', ['record' => $record]))
                    ->icon('heroicon-m-eye'),
            ]);
    }
}
