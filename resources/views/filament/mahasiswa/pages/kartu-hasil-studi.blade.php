<x-filament-panels::page>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- Informasi Mahasiswa -->
        <x-filament::section>
            <x-slot name="heading">
                Informasi Mahasiswa
            </x-slot>

            @if($mahasiswa)
            <dl class="grid grid-cols-1 gap-1 sm:grid-cols-2">
                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-gray-500">NIM</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $mahasiswa->nim }}</dd>
                </div>
                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-gray-500">Nama</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $mahasiswa->nama }}</dd>
                </div>
                <div class="sm:col-span-2">
                    <dt class="text-sm font-medium text-gray-500">Program Studi</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $mahasiswa->programStudi->nama }}</dd>
                </div>
            </dl>
            @else
            <div class="text-gray-500">
                Data mahasiswa tidak ditemukan.
            </div>
            @endif
        </x-filament::section>

        <!-- Informasi Semester -->
        <x-filament::section>
            <x-slot name="heading">
                Filter Kartu Hasil Studi
            </x-slot>

            {{ $this->form }}
        </x-filament::section>

        <!-- Informasi IP Semester -->
        <x-filament::section>
            <x-slot name="heading">
                Indeks Prestasi (IP)
            </x-slot>

            @if($mahasiswa && $selectedSemester)
            <dl class="grid grid-cols-1 gap-1">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Semester</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $selectedSemester }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Total SKS</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $totalSKS }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Total Nilai (Bobot)</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ number_format($totalNilai, 2) }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">IP Semester</dt>
                    <dd class="mt-1 text-sm font-medium text-lg text-primary-600">{{ number_format($ipSemester, 2) }}</dd>
                </div>
            </dl>
            @else
            <div class="text-gray-500">
                Pilih semester untuk melihat IP semester.
            </div>
            @endif
        </x-filament::section>
    </div>

    <!-- Daftar Mata Kuliah dan Nilai -->
    <x-filament::section class="mt-4">
        <x-slot name="heading">
            Daftar Mata Kuliah dan Nilai Semester {{ $selectedSemester ?? '-' }}
        </x-slot>

        {{ $this->table }}
    </x-filament::section>
</x-filament-panels::page>