<x-filament-panels::page>
    @if(!$selectedDosen)
        <div class="flex flex-col items-center justify-center min-h-[400px] space-y-8">
            <!-- Icon dan Judul -->
            <div class="text-center space-y-4">
                <div class="mx-auto w-16 h-16 bg-gray-200 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <div class="space-y-2">
                    <h3 class="text-xl font-semibold text-gray-900">Pilih Dosen Pembimbing</h3>
                    <p class="text-gray-500 max-w-md">Pilih dosen untuk melihat dan mengelola daftar mahasiswa bimbingan</p>
                </div>
            </div>

            <!-- Skeleton Cards -->
            <div class="w-full max-w-4xl space-y-4">
                <!-- Skeleton untuk daftar dosen -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @for($i = 0; $i < 6; $i++)
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 animate-pulse">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-gray-200 rounded-full"></div>
                                <div class="flex-1 space-y-2">
                                    <div class="h-4 bg-gray-200 rounded w-3/4"></div>
                                    <div class="h-3 bg-gray-200 rounded w-1/2"></div>
                                    <div class="h-3 bg-gray-200 rounded w-2/3"></div>
                                </div>
                            </div>
                        </div>
                    @endfor
                </div>
            </div>

            <!-- Tombol Pilih Dosen -->
            <div class="text-center">
                <x-filament::button
                    x-data=""
                    x-on:click="$dispatch('open-modal', { id: 'pilih-dosen' })"
                    size="lg"
                    class="shadow-lg"
                >
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Pilih Dosen
                </x-filament::button>
            </div>
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
                    <h3 class="text-lg font-medium mb-4">Daftar Mahasiswa Tersedia</h3>
                    {{ $this->table }}
                </div>
                <div class="w-1/2">
                    <h3 class="text-lg font-medium mb-4">Daftar Mahasiswa Bimbingan</h3>
                    <div class="bg-white rounded-lg shadow">
                        <div class="p-6">
                            <table class="min-w-full divide-y divide-gray-200" width="100%">
                                <thead>
                                    <tr>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mahasiswa</th>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($this->mahasiswaBimbingan as $mahasiswa)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $mahasiswa->nim }} - {{ $mahasiswa->nama }} <p class="text-xs text-gray-500">{{ $mahasiswa->programStudi->nama }}</p></td>
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