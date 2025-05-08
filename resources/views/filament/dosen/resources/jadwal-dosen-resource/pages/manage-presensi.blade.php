<x-filament-panels::page>
    <x-filament::section>
        <div class="flex justify-between items-center mb-4">
            <div>
                <h2 class="text-xl font-bold">{{ $record->mataKuliah->nama }}</h2>
                <div class="text-sm text-gray-500">
                    {{ $record->hari }}, {{ $record->jam_mulai->format('H:i') }} - {{ $record->jam_selesai->format('H:i') }} | 
                    Kelas: {{ $record->kelas }} | 
                    Ruangan: {{ $record->ruangan->nama }}
                </div>
            </div>
            <div>
                <span class="px-3 py-1 text-xs font-medium rounded-full bg-primary-50 text-primary-600">
                    {{ $record->tahunAkademik->nama }}
                </span>
            </div>
        </div>
    </x-filament::section>

    <div class="mt-4">
        {{ $this->table }}
    </div>
</x-filament-panels::page> 