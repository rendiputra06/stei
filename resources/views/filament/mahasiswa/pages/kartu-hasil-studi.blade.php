<x-filament-panels::page>
    @if(!$semuaEvaluasiTerisi && $jadwalEdomAktif)
    <div class="p-4 mb-4 border border-warning-500 rounded-lg bg-warning-50 dark:bg-warning-900/20">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <x-heroicon-s-exclamation-triangle class="w-5 h-5 text-warning-400" />
            </div>
            <div class="ml-3 flex-1 md:flex md:justify-between">
                <p class="text-sm text-warning-700 dark:text-warning-400">
                    <strong>Perhatian!</strong> Anda tidak dapat melihat nilai KHS karena masih ada {{ count($evaluasiYangBelumDiisi) }} evaluasi dosen yang belum diisi.
                </p>
                <p class="mt-3 text-sm md:mt-0 md:ml-6">
                    <a href="{{ route('filament.mahasiswa.pages.pengisian-edom-page') }}" class="whitespace-nowrap font-medium text-warning-700 hover:text-warning-600 dark:text-warning-400 dark:hover:text-warning-300">
                        Isi Evaluasi Dosen <span aria-hidden="true">&rarr;</span>
                    </a>
                </p>
            </div>
        </div>
    </div>

    <div x-data="{ open: false }" class="mb-4">
        <button @click="open = !open" class="flex items-center text-sm font-medium text-primary-600 hover:text-primary-500">
            <span x-text="open ? 'Sembunyikan daftar evaluasi yang belum diisi' : 'Lihat daftar evaluasi yang belum diisi'"></span>
            <x-heroicon-s-chevron-down x-show="!open" class="w-5 h-5 ml-1" />
            <x-heroicon-s-chevron-up x-show="open" class="w-5 h-5 ml-1" />
        </button>
        <div x-show="open" class="mt-3 p-4 border border-gray-200 rounded-lg">
            <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Daftar Evaluasi Yang Belum Diisi:</h3>
            <ul class="space-y-2">
                @foreach($evaluasiYangBelumDiisi as $evaluasi)
                <li class="text-sm text-gray-600 dark:text-gray-400">
                    <div class="flex justify-between">
                        <div>
                            <span class="font-medium">{{ $evaluasi['mata_kuliah'] }}</span> - Kelas {{ $evaluasi['kelas'] }}
                        </div>
                        <div>
                            Dosen: {{ $evaluasi['dosen_nama'] }}
                        </div>
                    </div>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

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

            @if($mahasiswa && $selectedSemester && $semuaEvaluasiTerisi)
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
            @elseif(!$semuaEvaluasiTerisi && $jadwalEdomAktif)
            <div class="text-warning-500">
                <div class="flex items-center">
                    <x-heroicon-s-lock-closed class="w-5 h-5 mr-2" />
                    <span>Informasi IP semester tidak tersedia.</span>
                </div>
                <p class="text-sm mt-2">Silakan isi semua evaluasi dosen terlebih dahulu.</p>
            </div>
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

        @if(!$semuaEvaluasiTerisi && $jadwalEdomAktif)
        <div class="p-4 rounded-lg border border-warning-500 bg-warning-50 dark:bg-warning-900/20">
            <div class="flex items-center">
                <x-heroicon-s-lock-closed class="w-5 h-5 text-warning-500 mr-2" />
                <p class="text-warning-700 dark:text-warning-400">
                    Data nilai tidak dapat ditampilkan karena Anda belum mengisi semua evaluasi dosen.
                </p>
            </div>
        </div>
        @endif

        {{ $this->table }}
    </x-filament::section>
</x-filament-panels::page>