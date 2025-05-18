<x-filament-panels::page>
    <div class="space-y-6">
        @if ($jadwalAktif)
        <div class="p-6 bg-white rounded-xl shadow-sm">
            <div class="space-y-2">
                <h2 class="text-xl font-semibold text-gray-900">
                    {{ $jadwalAktif->nama_periode }}
                </h2>
                <p class="text-gray-600">
                    Periode: {{ $jadwalAktif->tanggal_mulai->format('d/m/Y') }} - {{ $jadwalAktif->tanggal_selesai->format('d/m/Y') }}
                </p>
                @if ($jadwalAktif->deskripsi)
                <div class="mt-2 text-sm text-gray-600">
                    {{ $jadwalAktif->deskripsi }}
                </div>
                @endif
            </div>
        </div>

        <div class="flex justify-between items-center">
            <h3 class="text-lg font-medium text-gray-900">
                Daftar Dosen yang Dapat Dievaluasi
            </h3>
            <button wire:click="refreshDosenList" class="px-4 py-2 text-sm font-medium text-white bg-primary-600 rounded-lg hover:bg-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-500">
                <span class="flex items-center gap-1">
                    <x-heroicon-o-arrow-path class="w-4 h-4" />
                    Refresh
                </span>
            </button>
        </div>

        @if (count($dosenList) > 0)
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3">
            @foreach ($dosenList as $index => $item)
            <div class="p-6 bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow">
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <h4 class="text-base font-semibold text-gray-900">
                            {{ $item['dosen_nama'] }}
                        </h4>
                    </div>
                    <div class="space-y-1">
                        <p class="text-sm text-gray-600">
                            <span class="font-medium">Mata Kuliah:</span> {{ $item['mata_kuliah'] }}
                        </p>
                        <p class="text-sm text-gray-600">
                            <span class="font-medium">Kelas:</span> {{ $item['kelas'] }}
                        </p>
                    </div>
                    <div class="pt-2">
                        {{--
                            Metode utama: wire:click ke isiEvaluasi yang mengirimkan hanya ID jadwal kuliah
                            Metode ini akan mencari parameter lain di server dan melakukan redirect
                        --}}
                        <button
                            wire:click="isiEvaluasi({{ $item['id'] }})"
                            class="w-full px-4 py-2 text-sm font-medium text-white bg-primary-600 rounded-lg hover:bg-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-500">
                            Isi Evaluasi
                        </button>

                        {{--
                            Tombol alternatif dengan metode navigasi JS jika metode redirect biasa gagal
                            Mengirimkan semua parameter yang dibutuhkan 
                        --}}
                        <button
                            wire:click="navigateToEvaluasi({{ $item['id'] }}, {{ $item['dosen_id'] }}, {{ $item['mata_kuliah_id'] }})"
                            style="display: none;"
                            id="alt-btn-{{ $item['id'] }}"
                            class="w-full px-4 py-2 text-sm font-medium text-white bg-primary-600 rounded-lg hover:bg-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-500">
                            Isi Evaluasi (Alt)
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="p-12 text-center bg-white rounded-xl shadow-sm">
            <div class="flex justify-center mb-4">
                <x-heroicon-o-clipboard-document-check class="w-11 h-11 text-gray-400" />
            </div>
            <h3 class="text-lg font-medium text-gray-900">
                Semua evaluasi sudah diisi
            </h3>
            <p class="mt-2 text-sm text-gray-600">
                Anda telah mengisi semua evaluasi untuk periode ini. Terima kasih atas partisipasi Anda.
            </p>
            <p class="mt-4">
                <a href="{{ route('filament.mahasiswa.pages.edom-riwayat-page') }}" class="text-primary-600 hover:text-primary-500">
                    Lihat Riwayat Evaluasi →
                </a>
            </p>
        </div>
        @endif
        @else
        <div class="p-12 text-center bg-white rounded-xl shadow-sm">
            <div class="flex justify-center mb-4">
                <x-heroicon-o-calendar class="w-12 h-12 text-gray-400" />
            </div>
            <h3 class="text-lg font-medium text-gray-900">
                Tidak Ada Periode EDOM Yang Aktif
            </h3>
            <p class="mt-2 text-sm text-gray-600">
                Saat ini belum ada periode evaluasi dosen yang aktif. Silakan periksa kembali nanti.
            </p>
        </div>
        @endif
    </div>

    {{-- Script untuk menangani navigasi --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Listener untuk event navigasi yang dikirim oleh Livewire
            document.addEventListener('livewire:navigateToUrl', function(event) {
                if (event.detail && event.detail.url) {
                    window.location.href = event.detail.url;
                }
            });
        });
    </script>
</x-filament-panels::page>