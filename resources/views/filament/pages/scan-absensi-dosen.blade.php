<x-filament-panels::page>
    <div class="flex flex-col items-center justify-center space-y-6">
        <div class="text-center mb-4">
            <h2 class="text-2xl font-bold">Scan QR Code Absensi Dosen</h2>
            <p class="text-gray-500">Arahkan kamera ke QR Code yang ditampilkan oleh dosen</p>
        </div>

        <div id="scanner-container" class="w-full max-w-md mx-auto p-4 bg-white rounded-xl shadow-md border border-gray-200">
            <div id="reader" class="w-full h-80"></div>
        </div>

        <div id="result-container" class="w-full max-w-md mx-auto p-6 bg-white rounded-xl shadow-md border border-gray-200 hidden">
            <div id="result-status" class="text-center mb-4">
                <div id="status-icon" class="mx-auto w-16 h-16 flex items-center justify-center rounded-full mb-2"></div>
                <h3 id="status-message" class="text-xl font-semibold"></h3>
            </div>

            <div class="space-y-4">
                <div class="grid grid-cols-3 gap-4">
                    <div class="font-semibold">Nama Dosen</div>
                    <div class="col-span-2" id="dosen-name">{{ $dosenName ?? '-' }}</div>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <div class="font-semibold">Jadwal</div>
                    <div class="col-span-2" id="jadwal-info">{{ $jadwalInfo ?? '-' }}</div>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <div class="font-semibold">Waktu</div>
                    <div class="col-span-2" id="scan-time">-</div>
                </div>
            </div>

            <div class="flex justify-center mt-6">
                <button id="scan-again" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2">
                    Scan Lagi
                </button>
            </div>
        </div>
    </div>

    <!-- HTML5 QR Code Scanner Library -->
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

    <script>
        let html5QrCode;

        document.addEventListener('DOMContentLoaded', function() {
            initScanner();

            document.getElementById('scan-again').addEventListener('click', function() {
                document.getElementById('scanner-container').classList.remove('hidden');
                document.getElementById('result-container').classList.add('hidden');
                initScanner();
            });
        });

        function initScanner() {
            const qrboxFunction = function(viewfinderWidth, viewfinderHeight) {
                let minEdgePercentage = 0.7; // 70%
                let minEdgeSize = Math.min(viewfinderWidth, viewfinderHeight);
                let qrboxSize = Math.floor(minEdgeSize * minEdgePercentage);
                return {
                    width: qrboxSize,
                    height: qrboxSize
                };
            };

            html5QrCode = new Html5Qrcode("reader");
            html5QrCode.start({
                    facingMode: "environment"
                }, {
                    fps: 10,
                    qrbox: qrboxFunction,
                },
                onScanSuccess,
                onScanFailure
            );
        }

        function stopScanner() {
            if (html5QrCode) {
                html5QrCode.stop().catch(error => {
                    console.error('Error stopping scanner:', error);
                });
            }
        }

        function onScanSuccess(decodedText, decodedResult) {
            stopScanner();

            // Proses hasil scan menggunakan Livewire
            window.Livewire.dispatch('processQRCode', {
                qrData: decodedText
            });
        }

        function onScanFailure(error) {
            // Tidak perlu melakukan apa-apa pada kegagalan scan
        }

        // Event listener untuk menampilkan hasil
        document.addEventListener('showResult', function(e) {
            const status = e.detail.status;
            const statusIcon = document.getElementById('status-icon');
            const statusMessage = document.getElementById('status-message');
            const scanTime = document.getElementById('scan-time');

            // Set waktu scan
            scanTime.textContent = new Date().toLocaleTimeString();

            // Set ikon dan pesan status
            if (status === 'success-in') {
                statusIcon.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-success-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>';
                statusIcon.className = 'mx-auto w-16 h-16 flex items-center justify-center rounded-full mb-2 bg-success-100';
                statusMessage.textContent = 'Berhasil melakukan absensi masuk';
                statusMessage.className = 'text-xl font-semibold text-success-600';
            } else if (status === 'success-out') {
                statusIcon.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-success-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>';
                statusIcon.className = 'mx-auto w-16 h-16 flex items-center justify-center rounded-full mb-2 bg-success-100';
                statusMessage.textContent = 'Berhasil melakukan absensi keluar';
                statusMessage.className = 'text-xl font-semibold text-success-600';
            } else if (status === 'warning') {
                statusIcon.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-warning-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>';
                statusIcon.className = 'mx-auto w-16 h-16 flex items-center justify-center rounded-full mb-2 bg-warning-100';
                statusMessage.textContent = 'Sudah melakukan absensi hari ini';
                statusMessage.className = 'text-xl font-semibold text-warning-600';
            } else {
                statusIcon.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-danger-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>';
                statusIcon.className = 'mx-auto w-16 h-16 flex items-center justify-center rounded-full mb-2 bg-danger-100';
                statusMessage.textContent = 'Terjadi kesalahan';
                statusMessage.className = 'text-xl font-semibold text-danger-600';
            }

            // Setelah menampilkan hasil, perbarui halaman untuk mendapatkan data terbaru
            window.Livewire.dispatch('$refresh');

            // Tampilkan hasil dan sembunyikan scanner
            document.getElementById('scanner-container').classList.add('hidden');
            document.getElementById('result-container').classList.remove('hidden');
        });
    </script>
</x-filament-panels::page>