<x-filament-panels::page>
    <x-filament::section>
        <h2 class="text-xl font-bold tracking-tight">Selamat Datang di Panel Mahasiswa</h2>
        <p class="mt-2 text-gray-600">
            Gunakan panel ini untuk mengakses informasi akademik Anda.
        </p>
    </x-filament::section>

    <div class="mt-6 grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
        <x-filament::section>
            <div class="flex items-center gap-x-3">
                <div class="rounded-lg bg-blue-500/10 p-3 text-blue-500">
                    <x-heroicon-o-academic-cap class="h-6 w-6" />
                </div>
                <div>
                    <h3 class="text-base font-semibold">Mata Kuliah</h3>
                    <p class="text-sm text-gray-500">Lihat mata kuliah yang Anda ambil</p>
                </div>
            </div>
        </x-filament::section>

        <x-filament::section>
            <div class="flex items-center gap-x-3">
                <div class="rounded-lg bg-green-500/10 p-3 text-green-500">
                    <x-heroicon-o-clipboard-document-list class="h-6 w-6" />
                </div>
                <div>
                    <h3 class="text-base font-semibold">Nilai</h3>
                    <p class="text-sm text-gray-500">Lihat nilai akademik Anda</p>
                </div>
            </div>
        </x-filament::section>

        <x-filament::section>
            <div class="flex items-center gap-x-3">
                <div class="rounded-lg bg-amber-500/10 p-3 text-amber-500">
                    <x-heroicon-o-calendar class="h-6 w-6" />
                </div>
                <div>
                    <h3 class="text-base font-semibold">Jadwal</h3>
                    <p class="text-sm text-gray-500">Lihat jadwal perkuliahan Anda</p>
                </div>
            </div>
        </x-filament::section>
    </div>
</x-filament-panels::page> 