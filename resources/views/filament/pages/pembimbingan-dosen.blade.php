<x-filament-panels::page>
    @if(!$selectedDosen)
        <div class="flex items-center justify-center h-full">
            <x-filament::button
                x-data=""
                x-on:click="$dispatch('open-modal', { id: 'pilih-dosen' })"
            >
                Pilih Dosen
            </x-filament::button>
        </div>
    @else
        <div class="space-y-6">
            <x-filament::section>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium text-gray-700">Nama</label>
                        <div class="mt-1 text-gray-900">{{ $selectedDosen->nama }}</div>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700">NIDN</label>
                        <div class="mt-1 text-gray-900">{{ $selectedDosen->nidn }}</div>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700">Program Studi</label>
                        <div class="mt-1 text-gray-900">{{ $selectedDosen->programStudi->nama }}</div>
                    </div>
                    <div class="flex items-end">
                        <x-filament::button
                            wire:click="unsetDosen"
                            color="gray"
                        >
                            Pilih Dosen Lain
                        </x-filament::button>
                    </div>
                </div>
            </x-filament::section>

            <div class="flex gap-6">
                <div class="w-1/2">
                    <h3 class="text-lg font-medium">Daftar Mahasiswa Tersedia</h3>
                    {{ $this->table }}
                </div>
                <div class="w-1/2">
                    <h3 class="text-lg font-medium">Daftar Mahasiswa Bimbingan</h3>
                    <div class="bg-white rounded-lg shadow">
                        <div class="p-6">
                            <table class="min-w-full divide-y divide-gray-200" width="100%">
                                <thead>
                                    <tr>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIM</th>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($mahasiswaBimbingan as $mahasiswa)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $mahasiswa->nim }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $mahasiswa->nama }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                <x-filament::button
                                                    wire:click="hapusBimbingan({{ $mahasiswa->id }})"
                                                    color="danger"
                                                    size="sm"
                                                >
                                                    Hapus
                                                </x-filament::button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <x-filament::modal id="pilih-dosen" width="4xl">
        <x-slot name="heading">
            Pilih Dosen
        </x-slot>

        <x-slot name="description">
            Pilih dosen untuk melihat daftar mahasiswa bimbingan
        </x-slot>

        <div class="space-y-4">
            <x-filament::input.wrapper>
                <x-filament::input.select
                    wire:model.live="filterProgramStudi"
                    label="Program Studi"
                >
                    <option value="">Semua Program Studi</option>
                    @foreach(\App\Models\ProgramStudi::all() as $programStudi)
                        <option value="{{ $programStudi->id }}">{{ $programStudi->nama }}</option>
                    @endforeach
                </x-filament::input.select>
            </x-filament::input.wrapper>

            <div class="grid grid-cols-1 gap-4">
                @foreach($this->getDosenList() as $dosen)
                    <div class="p-4 bg-white rounded-lg shadow hover:shadow-md transition-shadow cursor-pointer"
                         wire:click="selectDosen({{ $dosen->id }})"
                         x-on:click="$dispatch('close-modal', { id: 'pilih-dosen' })"
                    >
                        <div class="flex items-center justify-between">
                            <div>
                                <h4 class="text-lg font-medium">{{ $dosen->nama }}</h4>
                                <p class="text-sm text-gray-500">{{ $dosen->nidn }}</p>
                                <p class="text-sm text-gray-500">{{ $dosen->programStudi->nama }}</p>
                            </div>
                            <div class="text-sm text-gray-500">
                                {{ $dosen->pembimbingan_count }} Mahasiswa
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </x-filament::modal>
</x-filament-panels::page> 