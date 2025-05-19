<x-filament-panels::page>
    <div class="flex flex-col items-center justify-center p-4 md:p-6 space-y-6">
        @if($statusMessage)
        <div class="text-center p-4 text-lg font-semibold 
                @if(str_contains($statusMessage, 'Sedang berlangsung'))
                    text-success-600 bg-success-50 border border-success-300 rounded-lg
                @elseif(str_contains($statusMessage, 'Jadwal berikutnya'))
                    text-warning-600 bg-warning-50 border border-warning-300 rounded-lg
                @else
                    text-gray-600 bg-gray-50 border border-gray-300 rounded-lg
                @endif
                w-full max-w-4xl">
            {{ $statusMessage }}
        </div>
        @endif

        @if($currentJadwal || $nextJadwal)
        <div class="flex flex-col items-center max-w-md mx-auto p-6 bg-white rounded-xl shadow-md border border-gray-200">
            <h3 class="text-xl font-semibold mb-2">
                {{ ($currentJadwal ? $currentJadwal->mataKuliah->nama : $nextJadwal->mataKuliah->nama) }}
            </h3>

            <div class="text-sm text-gray-600 mb-4">
                @if($jenisTampilan === 'sekarang')
                <span class="px-2 py-1 bg-success-100 text-success-800 rounded-full">Sedang Berlangsung</span>
                @elseif($jenisTampilan === 'berikutnya')
                <span class="px-2 py-1 bg-warning-100 text-warning-800 rounded-full">{{ $countdownTime }}</span>
                @elseif($jenisTampilan === 'jadwal_akan_datang')
                <div class="flex flex-col items-center gap-1">
                    <span class="px-2 py-1 bg-info-100 text-info-800 rounded-full">{{ $nextJadwal->hari }}</span>
                    <span class="px-2 py-1 bg-warning-100 text-warning-800 rounded-full">{{ $countdownTime }}</span>
                </div>
                @endif
            </div>

            @if($qrCodeData)
            <div class="qr-container p-3 bg-white border border-gray-300 rounded-lg">
                <img id="qrcode-img" src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ urlencode($qrCodeData) }}" alt="QR Code Absensi" class="w-64 h-64">
            </div>
            <div class="mt-4 text-sm text-gray-500">
                @if($jenisTampilan === 'jadwal_akan_datang')
                QR Code untuk jadwal yang akan datang ({{ $nextJadwal->hari }})
                @else
                QR Code akan otomatis diperbarui setiap 5 menit
                @endif
            </div>
            @else
            <div class="text-center text-gray-500 p-4">
                QR Code tidak tersedia
            </div>
            @endif

            <div class="mt-4 text-xs text-gray-500">
                @if($jenisTampilan === 'jadwal_akan_datang')
                <div class="text-center mb-2">
                    <span class="font-semibold">Jadwal pada {{ $nextJadwal->hari }}</span><br>
                    {{ $nextJadwal->jam_mulai->format('H:i') }} - {{ $nextJadwal->jam_selesai->format('H:i') }} di {{ $nextJadwal->ruangan->nama ?? 'Ruang -' }}
                </div>
                @endif
            </div>

            <button type="button" class="mt-4 px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2" onclick="refreshQR()">
                Perbarui QR Code
            </button>
        </div>
        @else
        <div class="flex flex-col items-center max-w-md mx-auto p-6 bg-white rounded-xl shadow-md border border-gray-200">
            <div class="text-center text-gray-500 p-4">
                Tidak ada jadwal yang tersedia saat ini dan beberapa hari ke depan.
            </div>
        </div>
        @endif
    </div>

    <script>
        // Refresh QR code setiap 5 menit
        const refreshInterval = 5 * 60 * 1000; // 5 menit dalam milidetik
        let timer;

        function refreshQR() {
            clearInterval(timer);
            Livewire.dispatch('refreshQRCode');
            startTimer();
        }

        function startTimer() {
            timer = setInterval(refreshQR, refreshInterval);
        }

        // Mulai timer ketika halaman dimuat
        document.addEventListener('DOMContentLoaded', () => {
            startTimer();
        });

        // Hentikan timer ketika halaman tidak aktif
        document.addEventListener('visibilitychange', () => {
            if (document.visibilityState === 'hidden') {
                clearInterval(timer);
            } else {
                refreshQR(); // Refresh langsung ketika halaman aktif kembali
            }
        });
    </script>
</x-filament-panels::page>