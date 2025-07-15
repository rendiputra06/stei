<?php

namespace App\Filament\Pages;

use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\Pembimbingan;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class PembimbinganDosen extends Page implements Tables\Contracts\HasTable
{
    use Tables\Concerns\InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationLabel = 'Pembimbingan Dosen';
    protected static ?string $title = 'Pembimbingan Dosen';
    protected static ?string $slug = 'pembimbingan-dosen';

    protected static string $view = 'filament.pages.pembimbingan-dosen';

    public ?Dosen $selectedDosen = null;
    public $filterProgramStudi = '';

    public function getTableQuery(): Builder
    {
        return Mahasiswa::query()
            ->whereDoesntHave('pembimbingan')
            ->when($this->selectedDosen, function ($query) {
                return $query->whereNotIn('id', function ($subquery) {
                    $subquery->select('mahasiswa_id')
                        ->from('pembimbingan')
                        ->where('dosen_id', $this->selectedDosen->id);
                });
            });
    }

    public function selectDosen($id): void
    {
        $this->selectedDosen = Dosen::find($id);
    }

    public function unsetDosen(): void
    {
        $this->selectedDosen = null;
    }

    public function getDosenList(): Collection
    {
        $query = Dosen::query()
            ->withCount('pembimbingan');

        if ($this->filterProgramStudi) {
            $query->where('program_studi_id', $this->filterProgramStudi);
        }

        return $query->get();
    }

    public function getMahasiswaBimbinganProperty()
    {
        if (!$this->selectedDosen) {
            return collect();
        }

        // Ambil mahasiswa bimbingan dengan relasi program studi-nya
        return Mahasiswa::whereHas('pembimbingan', function ($query) {
            $query->where('dosen_id', $this->selectedDosen->id);
        })->with('programStudi')->get();
    }

    public function tambahBimbingan($mahasiswaId): void
    {
        Pembimbingan::create([
            'dosen_id' => $this->selectedDosen->id,
            'mahasiswa_id' => $mahasiswaId,
        ]);

        $this->dispatch('notify', [
            'message' => 'Mahasiswa berhasil ditambahkan sebagai bimbingan',
            'status' => 'success',
        ]);
    }

    public function hapusBimbingan($mahasiswaId): void
    {
        Pembimbingan::where('dosen_id', $this->selectedDosen->id)
            ->where('mahasiswa_id', $mahasiswaId)
            ->delete();

        $this->dispatch('notify', [
            'message' => 'Mahasiswa berhasil dihapus dari bimbingan',
            'status' => 'success',
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->label('Mahasiswa')
                    ->searchable(['nim', 'nama'])
                    ->sortable(['nim', 'nama'])
                    ->formatStateUsing(function ($record) {
                        return $record->nim . ' - ' . $record->nama;
                    })
                    ->description(fn($record) => $record->programStudi->nama),
                Tables\Columns\IconColumn::make('pembimbingan_count')
                    ->counts('pembimbingan')
                    ->label('Memiliki Pembimbing')
                    ->boolean()
            ])
            ->filters([
                // Anda dapat menambahkan filter di sini
            ])
            ->actions([
                Tables\Actions\Action::make('tambah')
                    ->label('Tambah')
                    ->button()
                    ->color('primary')
                    ->visible(fn($record) => $this->selectedDosen !== null)
                    ->action(fn($record) => $this->tambahBimbingan($record->id))
            ])
            ->bulkActions([
                // Anda dapat menambahkan bulk actions di sini
            ]);
    }
}
