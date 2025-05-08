@php
    $record->load(['mataKuliah', 'ruangan', 'tahunAkademik', 'dosen']);
@endphp

<x-filament-panels::page>
    <x-filament::section>
        <div class="flex justify-between items-center mb-4">
            <div>
                <h2 class="text-2xl font-bold">{{ $record->mataKuliah->nama }}</h2>
                <p class="text-md text-gray-600">{{ $record->mataKuliah->kode }} - {{ $record->mataKuliah->sks }} SKS</p>
            </div>
            <div>
                <span class="px-3 py-1 text-xs font-medium rounded-full bg-primary-50 text-primary-600">
                    {{ $record->tahunAkademik->nama }}
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
            <div>
                <h3 class="text-lg font-semibold mb-3">Informasi Jadwal</h3>
                <div class="bg-gray-50 rounded-lg p-4 shadow-sm">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Hari</p>
                            <p class="text-base">{{ $record->hari }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Waktu</p>
                            <p class="text-base">{{ $record->jam_mulai->format('H:i') }} - {{ $record->jam_selesai->format('H:i') }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Ruangan</p>
                            <p class="text-base">{{ $record->ruangan->nama }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Kelas</p>
                            <p class="text-base">{{ $record->kelas }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <h3 class="text-lg font-semibold mb-3">Dosen Pengajar</h3>
                <div class="bg-gray-50 rounded-lg p-4 shadow-sm">
                    <div class="flex items-center space-x-4">
                        <div class="rounded-full bg-primary-100 text-primary-600 p-3">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5" />
                            </svg>
                        </div>
                        <div>
                            <p class="font-semibold">{{ $record->dosen->nama }}</p>
                            <p class="text-sm text-gray-500">{{ $record->dosen->nip }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-8">
            <h3 class="text-lg font-semibold mb-3">Status Kelas</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-blue-50 rounded-lg p-4 shadow-sm">
                    <p class="text-sm font-medium text-blue-700">Jumlah Mahasiswa</p>
                    <p class="text-3xl font-bold text-blue-800">{{ $record->jumlahMahasiswa() }}</p>
                </div>
                <div class="bg-green-50 rounded-lg p-4 shadow-sm">
                    <p class="text-sm font-medium text-green-700">Pertemuan</p>
                    <p class="text-3xl font-bold text-green-800">{{ $record->jumlahPertemuan() }} / 14</p>
                </div>
                <div class="bg-amber-50 rounded-lg p-4 shadow-sm">
                    <p class="text-sm font-medium text-amber-700">Status</p>
                    <p class="text-xl font-bold text-amber-800">{{ $record->is_active ? 'Aktif' : 'Tidak Aktif' }}</p>
                </div>
            </div>
        </div>
    </x-filament::section>

    <div class="mt-8 space-y-4">
        <x-filament::section>
            {{ $this->infolist }}
        </x-filament::section>
    </div>
</x-filament-panels::page> 