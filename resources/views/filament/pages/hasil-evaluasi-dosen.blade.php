<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Filter form -->
        <div class="bg-white rounded-lg shadow overflow-hidden dark:bg-gray-800">
            {{ $this->form }}
        </div>

        @if($selectedJadwalId)
        <!-- Ringkasan Evaluasi -->
        <x-filament::section>
            <x-slot name="heading">
                Ringkasan Hasil Evaluasi
            </x-slot>
            @if($periodeLaporan)
            <p class="mb-4 text-sm text-gray-500">
                Periode {{ $periodeLaporan->nama_periode }} ({{ $periodeLaporan->tahunAkademik?->nama ?? '-' }})
            </p>
            @endif
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="p-4 bg-gray-50 rounded-lg dark:bg-gray-800">
                    <div class="text-sm font-medium text-gray-500">Total Responden</div>
                    <div class="mt-1 text-xl font-semibold">{{ $totalResponden }} Mahasiswa</div>
                </div>
                <div class="p-4 bg-gray-50 rounded-lg dark:bg-gray-800">
                    <div class="text-sm font-medium text-gray-500">Total Pertanyaan</div>
                    <div class="mt-1 text-xl font-semibold">{{ count($hasilEvaluasi) }} Pertanyaan</div>
                </div>
                <div class="p-4 bg-gray-50 rounded-lg dark:bg-gray-800">
                    <div class="text-sm font-medium text-gray-500">Rata-rata Nilai</div>
                    <div class="mt-1 text-xl font-semibold 
                            @if($rataRataTotal >= 4) text-success-600
                            @elseif($rataRataTotal >= 3) text-primary-600
                            @elseif($rataRataTotal >= 2) text-warning-600
                            @else text-danger-600
                            @endif">
                        {{ number_format($rataRataTotal, 2) }} / 5
                    </div>
                </div>
            </div>
        </x-filament::section>

        <!-- Rincian Evaluasi -->
        <x-filament::section>
            <x-slot name="heading">
                Detail Hasil Evaluasi Per Item
            </x-slot>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b">
                            <th class="text-left py-2 px-3">Kode</th>
                            <th class="text-left py-2 px-3">Pertanyaan</th>
                            <th class="text-center py-2 px-3">Rata-rata</th>
                            <th class="text-center py-2 px-3">Visualisasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($hasilEvaluasi as $hasil)
                        <tr class="border-b">
                            <td class="py-2 px-3 whitespace-nowrap">{{ $hasil['kode'] }}</td>
                            <td class="py-2 px-3">{{ $hasil['pertanyaan'] }}</td>
                            <td class="py-2 px-3 text-center whitespace-nowrap">
                                <span class="font-medium
                                            @if($hasil['rata_rata'] >= 4) text-success-600
                                            @elseif($hasil['rata_rata'] >= 3) text-primary-600
                                            @elseif($hasil['rata_rata'] >= 2) text-warning-600
                                            @else text-danger-600
                                            @endif">
                                    {{ number_format($hasil['rata_rata'], 2) }}
                                </span>
                            </td>
                            <td class="py-2 px-3 whitespace-nowrap">
                                <div class="w-full bg-gray-200 rounded-full h-2.5">
                                    <div class="h-2.5 rounded-full 
                                                @if($hasil['rata_rata'] >= 4) bg-success-600
                                                @elseif($hasil['rata_rata'] >= 3) bg-primary-600
                                                @elseif($hasil['rata_rata'] >= 2) bg-warning-500
                                                @else bg-danger-600
                                                @endif"
                                        style="width: {{ ($hasil['rata_rata'] / 5) * 100 }}%">
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="py-4 text-center text-sm text-gray-500">
                                Tidak ada data untuk ditampilkan
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-filament::section>

        <!-- Komentar -->
        <x-filament::section>
            <x-slot name="heading">
                Komentar dan Saran ({{ count($komentar) }})
            </x-slot>
            <div class="max-h-96 overflow-y-auto space-y-3">
                @forelse($komentar as $k)
                <div class="py-3 px-4 bg-gray-50 rounded-lg dark:bg-gray-800">
                    <p class="text-sm">"{{ $k['komentar'] }}"</p>
                    <p class="text-xs text-gray-500 mt-1">
                        {{ \Carbon\Carbon::parse($k['created_at'])->translatedFormat('d F Y H:i') }}
                    </p>
                </div>
                @empty
                <p class="text-center text-sm text-gray-500 py-4">
                    Tidak ada komentar untuk ditampilkan
                </p>
                @endforelse
            </div>
        </x-filament::section>
        @endif

        <!-- Tabel Detail -->
        <x-filament::section>
            <x-slot name="heading">
                Detail Pengisian Evaluasi
            </x-slot>

            {{ $this->table }}
        </x-filament::section>
    </div>
</x-filament-panels::page>