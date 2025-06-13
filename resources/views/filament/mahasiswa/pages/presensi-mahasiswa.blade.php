<x-filament-panels::page>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- Filter Presensi -->
        {{ $this->form }}

        <!-- Informasi Kehadiran -->
        <x-filament::section>
            <x-slot name="heading">
                Informasi Kehadiran
            </x-slot>

            @if($mahasiswa && $selectedJadwalId)
            <dl class="grid grid-cols-1 gap-1">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Total Pertemuan</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $totalPertemuan }} kali</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Total Hadir</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $totalHadir }} kali</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Persentase Kehadiran</dt>
                    <dd class="mt-1 text-sm font-medium">
                        <span class="{{ $persentaseKehadiran >= 80 ? 'text-success-600' : ($persentaseKehadiran >= 70 ? 'text-warning-600' : 'text-danger-600') }}">
                            {{ number_format($persentaseKehadiran, 2) }}%
                        </span>
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                    <dd class="mt-1">
                        <div class="{{ $persentaseKehadiran >= 80 ? 'bg-success-100 text-success-800' : ($persentaseKehadiran >= 70 ? 'bg-warning-100 text-warning-800' : 'bg-danger-100 text-danger-800') }} px-3 py-1 rounded-full text-xs font-medium inline-block">
                            {{ $persentaseKehadiran >= 80 ? 'Memenuhi Syarat' : ($persentaseKehadiran >= 70 ? 'Perlu Perhatian' : 'Tidak Memenuhi Syarat') }}
                        </div>
                    </dd>
                </div>
            </dl>
            @else
            <div class="text-gray-500">
                Pilih mata kuliah untuk melihat informasi kehadiran.
            </div>
            @endif
        </x-filament::section>
    </div>

    <!-- Informasi Mata Kuliah -->
    @if($jadwalDetail)
    <x-filament::section class="mt-4">
        <x-slot name="heading">
            Detail Mata Kuliah
        </x-slot>

        <dl class="grid grid-cols-1 gap-2 sm:grid-cols-2 md:grid-cols-4 mb-4">
            <div>
                <dt class="text-sm font-medium text-gray-500">Kode MK</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $jadwalDetail->mataKuliah->kode }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">Mata Kuliah</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $jadwalDetail->mataKuliah->nama }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">Dosen</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $jadwalDetail->dosen->nama }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">SKS</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $jadwalDetail->mataKuliah->sks }}</dd>
            </div>
            <div class="sm:col-span-2">
                <dt class="text-sm font-medium text-gray-500">Ruangan</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $jadwalDetail->ruangan->gedung->nama }} - {{ $jadwalDetail->ruangan->nama }}</dd>
            </div>
            <div class="sm:col-span-2">
                <dt class="text-sm font-medium text-gray-500">Jadwal</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $jadwalDetail->hari }} {{ $jadwalDetail->jam_mulai }} - {{ $jadwalDetail->jam_selesai }}</dd>
            </div>
        </dl>

        <div class="mb-4 p-4 bg-gray-50 rounded-lg border border-gray-200">
            <p class="text-sm text-gray-600">
                Berikut adalah daftar presensi untuk mata kuliah {{ $jadwalDetail->mataKuliah->nama }}.
                Minimal kehadiran yang disyaratkan adalah 80% dari total pertemuan.
            </p>
        </div>
    </x-filament::section>
    @endif

    <!-- Daftar Presensi -->
    <x-filament::section class="mt-4">
        <x-slot name="heading">
            Daftar Presensi Perkuliahan
        </x-slot>

        {{ $this->table }}
    </x-filament::section>
</x-filament-panels::page>