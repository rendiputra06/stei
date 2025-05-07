<x-filament-panels::page>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- Informasi Tahun Akademik -->
        <x-filament::section>
            <x-slot name="heading">
                Informasi Tahun Akademik
            </x-slot>
            
            @if($tahunAkademik)
                <dl class="grid grid-cols-1 gap-1 sm:grid-cols-2">
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">Tahun Akademik</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $tahunAkademik->nama }}</dd>
                    </div>
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">Semester</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $tahunAkademik->semester }}</dd>
                    </div>
                    <div class="sm:col-span-2">
                        <dt class="text-sm font-medium text-gray-500">Periode Akademik</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            {{ $tahunAkademik->tanggal_mulai->format('d M Y') }} - {{ $tahunAkademik->tanggal_selesai->format('d M Y') }}
                        </dd>
                    </div>
                </dl>
            @else
                <div class="text-gray-500">
                    Tidak ada tahun akademik aktif.
                </div>
            @endif
        </x-filament::section>
        
        <!-- Informasi Status Mahasiswa -->
        <x-filament::section>
            <x-slot name="heading">
                Status Mahasiswa
            </x-slot>
            
            @if($mahasiswa && $statusMahasiswa)
                <dl class="grid grid-cols-1 gap-1 sm:grid-cols-2">
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">NIM</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $mahasiswa->nim }}</dd>
                    </div>
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">Nama</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $mahasiswa->nama }}</dd>
                    </div>
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">Program Studi</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $mahasiswa->programStudi->nama }}</dd>
                    </div>
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">Semester</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $statusMahasiswa->semester }}</dd>
                    </div>
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">Status</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            <span @class([
                                'px-2 py-1 text-xs rounded-full',
                                'bg-green-100 text-green-800' => $statusMahasiswa->status === 'aktif',
                                'bg-yellow-100 text-yellow-800' => $statusMahasiswa->status === 'cuti',
                                'bg-red-100 text-red-800' => in_array($statusMahasiswa->status, ['tidak_aktif', 'drop_out']),
                                'bg-blue-100 text-blue-800' => $statusMahasiswa->status === 'lulus',
                            ])>
                                {{ $statusMahasiswa->status === 'tidak_aktif' ? 'Tidak Aktif' : ucfirst($statusMahasiswa->status) }}
                            </span>
                        </dd>
                    </div>
                </dl>
            @else
                <div class="text-gray-500">
                    Data mahasiswa tidak ditemukan.
                </div>
            @endif
        </x-filament::section>
        
        <!-- Informasi Periode KRS -->
        <x-filament::section>
            <x-slot name="heading">
                Periode Pengisian KRS
            </x-slot>
            
            @if($tahunAkademik)
                <dl class="grid grid-cols-1 gap-1">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Periode KRS</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            {{ $tahunAkademik->krs_mulai->format('d M Y H:i') }} - {{ $tahunAkademik->krs_selesai->format('d M Y H:i') }}
                        </dd>
                    </div>
                    <div class="mt-2">
                        <dt class="text-sm font-medium text-gray-500">Status KRS</dt>
                        <dd class="mt-1">
                            @php
                                $now = \Carbon\Carbon::now();
                                $inPeriod = $now->between($tahunAkademik->krs_mulai, $tahunAkademik->krs_selesai);
                                $beforePeriod = $now->lt($tahunAkademik->krs_mulai);
                                $afterPeriod = $now->gt($tahunAkademik->krs_selesai);
                            @endphp
                            
                            @if($inPeriod)
                                <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">
                                    Periode Pengisian KRS Sedang Berlangsung
                                </span>
                            @elseif($beforePeriod)
                                <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">
                                    Periode Pengisian KRS Belum Dimulai
                                </span>
                            @elseif($afterPeriod)
                                <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">
                                    Periode Pengisian KRS Sudah Berakhir
                                </span>
                            @endif
                        </dd>
                    </div>
                    
                    @if($krs)
                        <div class="mt-4">
                            <dt class="text-sm font-medium text-gray-500">Status Pengajuan KRS</dt>
                            <dd class="mt-1">
                                <span @class([
                                    'px-2 py-1 text-xs rounded-full',
                                    'bg-gray-100 text-gray-800' => $krs->status === 'draft',
                                    'bg-yellow-100 text-yellow-800' => $krs->status === 'submitted',
                                    'bg-green-100 text-green-800' => $krs->status === 'approved',
                                    'bg-red-100 text-red-800' => $krs->status === 'rejected',
                                ])>
                                    {{ ucfirst($krs->status) }}
                                </span>
                            </dd>
                        </div>
                        
                        <div class="mt-2">
                            <dt class="text-sm font-medium text-gray-500">Total SKS</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $krs->total_sks }}</dd>
                        </div>
                        
                        @if($krs->tanggal_submit)
                            <div class="mt-2">
                                <dt class="text-sm font-medium text-gray-500">Tanggal Submit</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $krs->tanggal_submit->format('d M Y H:i') }}</dd>
                            </div>
                        @endif
                        
                        @if($krs->tanggal_approval)
                            <div class="mt-2">
                                <dt class="text-sm font-medium text-gray-500">Tanggal Approval</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $krs->tanggal_approval->format('d M Y H:i') }}</dd>
                            </div>
                        @endif
                    @endif
                </dl>
            @else
                <div class="text-gray-500">
                    Tidak ada periode KRS saat ini.
                </div>
            @endif
        </x-filament::section>
    </div>
    
    <!-- Daftar Mata Kuliah -->
    <x-filament::section class="mt-4">
        <x-slot name="heading">
            Daftar Mata Kuliah yang Dipilih
        </x-slot>
        
        @if(!$krs)
            <div class="text-center py-4 text-gray-500">
                @if(!$tahunAkademik)
                    Tidak ada tahun akademik aktif.
                @elseif(!$statusMahasiswa || $statusMahasiswa->status !== 'aktif')
                    Status Anda tidak aktif. Tidak dapat mengisi KRS.
                @else
                    Tidak dapat membuat KRS. Periode pengisian KRS belum dimulai atau sudah berakhir.
                @endif
            </div>
        @else
            {{ $this->table }}
        @endif
    </x-filament::section>
</x-filament-panels::page> 