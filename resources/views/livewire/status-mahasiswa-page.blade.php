<div class="p-4">
    <div class="max-w-7xl mx-auto">
        <h1 class="text-2xl font-semibold mb-4">Status Mahasiswa</h1>
        
        @if($tahunAkademikAktif)
        <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 mb-6" role="alert">
            <p>Menampilkan status mahasiswa pada <strong>{{ $tahunAkademikAktif->nama }}</strong></p>
            <p class="text-sm mt-1">Periode: {{ $tahunAkademikAktif->tanggal_mulai->format('d M Y') }} - {{ $tahunAkademikAktif->tanggal_selesai->format('d M Y') }}</p>
        </div>
        @else
        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-6" role="alert">
            <p>Tidak ada tahun akademik yang aktif saat ini. Silakan pilih tahun akademik melalui filter di bawah.</p>
        </div>
        @endif
        
        <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
            <div class="md:col-span-2">
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <livewire:status-mahasiswa-table />
                </div>
            </div>
            
            <div class="md:col-span-1">
                <livewire:generate-status-mahasiswa />
                
                <div class="bg-white rounded-lg shadow-sm mt-6 p-4">
                    <h2 class="text-lg font-semibold mb-2">Keterangan Status</h2>
                    <ul class="space-y-2">
                        <li class="flex items-center">
                            <span class="w-3 h-3 bg-gray-300 rounded-full mr-2"></span>
                            <span>Tidak Aktif: Mahasiswa belum melakukan registrasi/KRS</span>
                        </li>
                        <li class="flex items-center">
                            <span class="w-3 h-3 bg-green-500 rounded-full mr-2"></span>
                            <span>Aktif: Mahasiswa aktif mengikuti perkuliahan</span>
                        </li>
                        <li class="flex items-center">
                            <span class="w-3 h-3 bg-yellow-500 rounded-full mr-2"></span>
                            <span>Cuti: Mahasiswa sedang dalam status cuti</span>
                        </li>
                        <li class="flex items-center">
                            <span class="w-3 h-3 bg-blue-500 rounded-full mr-2"></span>
                            <span>Lulus: Mahasiswa sudah lulus</span>
                        </li>
                        <li class="flex items-center">
                            <span class="w-3 h-3 bg-red-500 rounded-full mr-2"></span>
                            <span>Drop Out: Mahasiswa di-DO</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div> 