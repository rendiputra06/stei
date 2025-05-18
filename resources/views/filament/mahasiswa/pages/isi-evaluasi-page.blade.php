<x-filament-panels::page>
    @if(!$jadwalEdom || !$jadwalKuliah || !$dosen || !$mataKuliah)
    <div class="p-12 text-center bg-white rounded-xl shadow-sm">
        <div class="flex justify-center mb-4">
            <x-heroicon-o-exclamation-triangle class="w-12 h-12 text-amber-500" />
        </div>
        <h3 class="text-lg font-medium text-gray-900">
            Data Tidak Lengkap
        </h3>
        <p class="mt-2 text-sm text-gray-600">
            Terjadi kesalahan dalam memuat data. Silakan kembali ke halaman utama evaluasi.
        </p>
        <p class="mt-4">
            <a href="{{ route('filament.mahasiswa.pages.pengisian-edom-page') }}" class="text-primary-600 hover:text-primary-500">
                Kembali ke Daftar Evaluasi →
            </a>
        </p>
    </div>
    @else
    <div class="space-y-6">
        <div class="p-6 bg-white rounded-xl shadow-sm">
            <div class="space-y-2">
                <h2 class="text-xl font-semibold text-gray-900">
                    Formulir Evaluasi Dosen
                </h2>
                <div class="grid grid-cols-1 gap-4 mt-4 sm:grid-cols-2">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Periode Evaluasi</p>
                        <p class="text-base text-gray-900">{{ $jadwalEdom->nama_periode }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Dosen</p>
                        <p class="text-base text-gray-900">{{ $dosen->nama }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Mata Kuliah</p>
                        <p class="text-base text-gray-900">{{ $mataKuliah->nama_mata_kuliah }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Kelas</p>
                        <p class="text-base text-gray-900">{{ $jadwalKuliah->kelas }}</p>
                    </div>
                </div>
            </div>
        </div>

        @if($is_submitted)
        <div class="p-4 rounded-lg bg-amber-50 text-amber-700 border border-amber-200 mb-6">
            <div class="flex items-center">
                <x-heroicon-o-exclamation-triangle class="w-5 h-5 mr-2" />
                <div>
                    <p class="font-medium">Evaluasi Sudah Dikumpulkan</p>
                    <p class="text-sm mt-1">Evaluasi yang sudah dikumpulkan tidak dapat diubah lagi. Anda hanya dapat melihat jawaban yang telah diisi.</p>
                </div>
            </div>
        </div>
        @endif

        <div class="p-6 bg-white rounded-xl shadow-sm">
            <div class="space-y-2 mb-4">
                <h3 class="text-lg font-medium text-gray-900">
                    Petunjuk Pengisian
                </h3>
                <div class="text-sm text-gray-600">
                    <p>Berikanlah penilaian Anda terhadap kinerja dosen pada mata kuliah ini dengan jujur dan objektif.</p>
                    <div class="mt-2">
                        <p class="font-medium">Skala Penilaian:</p>
                        <ol class="list-decimal list-inside ml-2 space-y-1">
                            <li><span class="font-medium">1</span> - Sangat Tidak Setuju</li>
                            <li><span class="font-medium">2</span> - Tidak Setuju</li>
                            <li><span class="font-medium">3</span> - Netral</li>
                            <li><span class="font-medium">4</span> - Setuju</li>
                            <li><span class="font-medium">5</span> - Sangat Setuju</li>
                        </ol>
                    </div>
                </div>
            </div>

            {{ $this->form }}

            <div class="flex justify-end gap-3 mt-6">
                @if($is_submitted)
                <a href="{{ route('filament.mahasiswa.pages.edom-riwayat-page') }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-500">
                    Kembali ke Riwayat
                </a>
                @else
                <x-filament::button
                    type="button"
                    color="gray"
                    wire:click="$refresh"
                    class="filament-button-cancel">
                    Batal
                </x-filament::button>

                <x-filament::button
                    type="button"
                    color="secondary"
                    wire:click="simpan"
                    class="filament-button-save">
                    Simpan Draft
                </x-filament::button>

                <x-filament::button
                    type="button"
                    color="primary"
                    wire:click="submit"
                    class="filament-button-submit">
                    Kumpulkan
                </x-filament::button>
                @endif
            </div>
        </div>
    </div>
    @endif
</x-filament-panels::page>