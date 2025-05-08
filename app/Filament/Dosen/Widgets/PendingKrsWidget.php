<?php

namespace App\Filament\Dosen\Widgets;

use App\Models\KRS;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\Auth;

class PendingKrsWidget extends BaseWidget
{
    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                KRS::query()
                    ->whereHas('mahasiswa', function ($query) {
                        $query->whereHas('pembimbingan', function ($query) {
                            $query->where('dosen_id', Auth::user()->dosen->id);
                        });
                    })
                    ->where('status', 'submitted')
                    ->orderBy('tanggal_submit', 'desc')
            )
            ->heading('KRS Mahasiswa Menunggu Persetujuan')
            ->emptyStateHeading('Tidak ada KRS yang menunggu persetujuan')
            ->emptyStateDescription('Semua KRS mahasiswa bimbingan Anda sudah disetujui atau ditolak.')
            ->columns([
                Tables\Columns\TextColumn::make('mahasiswa.nim')
                    ->label('NIM')
                    ->searchable(),
                Tables\Columns\TextColumn::make('mahasiswa.nama')
                    ->label('Nama Mahasiswa')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('semester')
                    ->sortable(),
                Tables\Columns\TextColumn::make('tahunAkademik.nama')
                    ->label('Tahun Akademik')
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_sks')
                    ->label('Total SKS')
                    ->sortable(),
                Tables\Columns\TextColumn::make('tanggal_submit')
                    ->label('Tanggal Pengajuan')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->label('Lihat')
                    ->url(fn(KRS $record): string => route('filament.dosen.resources.krs-mahasiswas.view', ['record' => $record]))
                    ->icon('heroicon-m-eye'),
                Tables\Actions\Action::make('setujui')
                    ->label('Setujui')
                    ->url(fn(KRS $record): string => route('filament.dosen.resources.krs-mahasiswas.edit', ['record' => $record]))
                    ->icon('heroicon-m-check-circle')
                    ->color('success'),
            ]);
    }
}
