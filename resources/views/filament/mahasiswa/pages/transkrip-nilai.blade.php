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
                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-gray-500">Angkatan</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $mahasiswa->tahun_masuk }}</dd>
                </div>
                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-gray-500">Semester</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $semesterTertinggi }}</dd>
                </div>
            </dl>
            @else
            <div class="text-gray-500">
                Data mahasiswa tidak ditemukan.
            </div>
            @endif
        </x-filament::section>

        <!-- Informasi IPK -->
        <x-filament::section class="md:col-span-2">
            <x-slot name="heading">
                Indeks Prestasi Kumulatif (IPK)
            </x-slot>

            @if($mahasiswa)
            <dl class="grid grid-cols-1 gap-1 md:grid-cols-3">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Total SKS</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $totalSKS }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Total Nilai (Bobot)</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ number_format($totalNilai, 2) }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">IPK</dt>
                    <dd class="mt-1 text-lg font-bold text-primary-600">{{ number_format($ipk, 2) }}</dd>
                </div>
            </dl>
            @else
            <div class="text-gray-500">
                Data IPK tidak tersedia.
            </div>
            @endif
        </x-filament::section>
    </div>

    <!-- Daftar Mata Kuliah dan Nilai -->
    <x-filament::section class="mt-4">
        <x-slot name="heading">
            Transkrip Nilai Akademik
        </x-slot>

        <div class="mb-4 p-4 bg-gray-50 rounded-lg border border-gray-200">
            <p class="text-sm text-gray-600">
                Transkrip ini menampilkan seluruh nilai mata kuliah yang telah ditempuh selama masa studi.
            </p>
        </div>

        {{ $this->table }}
    </x-filament::section>
</x-filament-panels::page>