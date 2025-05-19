<x-filament-panels::page>
    <div class="space-y-6">
        <div class="p-4 md:p-6 bg-white rounded-xl shadow-sm border border-gray-200">
            <h2 class="text-xl font-bold mb-4">Laporan Absensi Dosen</h2>
            <p class="text-gray-500 mb-2">Laporan ini menampilkan riwayat kehadiran dosen berdasarkan QR code yang telah di-scan.</p>
            <p class="text-gray-500">Gunakan filter untuk melihat data spesifik berdasarkan dosen, periode waktu, atau status kehadiran.</p>
        </div>

        {{ $this->table }}
    </div>
</x-filament-panels::page>