<div class="p-4">
    <div class="mb-4">
        <div class="flex justify-between mb-4">
            <div>
                <h3 class="text-lg font-semibold">Pertemuan {{ $presensi->pertemuan_ke }}</h3>
                <p class="text-sm text-gray-500">{{ $presensi->tanggal->format('d M Y') }}</p>
            </div>
            <div>
                <span class="px-3 py-1 text-xs font-medium rounded-full bg-primary-50 text-primary-600">
                    Total: {{ $details->count() }} mahasiswa
                </span>
            </div>
        </div>
        
        <div class="p-2 mb-4 bg-gray-50 rounded-lg">
            <p class="text-sm text-gray-600">{{ $presensi->keterangan ?: 'Tidak ada keterangan' }}</p>
        </div>
    </div>

    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3">No</th>
                    <th scope="col" class="px-6 py-3">NIM</th>
                    <th scope="col" class="px-6 py-3">Nama Mahasiswa</th>
                    <th scope="col" class="px-6 py-3">Status</th>
                    <th scope="col" class="px-6 py-3">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($details as $index => $detail)
                    <tr class="bg-white border-b hover:bg-gray-50">
                        <td class="px-6 py-4">{{ $index + 1 }}</td>
                        <td class="px-6 py-4">{{ $detail->mahasiswa->nim }}</td>
                        <td class="px-6 py-4">{{ $detail->mahasiswa->nama }}</td>
                        <td class="px-6 py-4">
                            @php
                                $statusClass = match($detail->status) {
                                    'hadir' => 'bg-green-100 text-green-800',
                                    'izin' => 'bg-yellow-100 text-yellow-800',
                                    'sakit' => 'bg-blue-100 text-blue-800',
                                    'alpa' => 'bg-red-100 text-red-800',
                                    default => 'bg-gray-100 text-gray-800'
                                };
                                $statusLabel = match($detail->status) {
                                    'hadir' => 'Hadir',
                                    'izin' => 'Izin',
                                    'sakit' => 'Sakit',
                                    'alpa' => 'Tanpa Keterangan',
                                    default => $detail->status
                                };
                            @endphp
                            <span class="px-2 py-1 text-xs font-medium rounded-full {{ $statusClass }}">
                                {{ $statusLabel }}
                            </span>
                        </td>
                        <td class="px-6 py-4">{{ $detail->keterangan ?: '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div> 