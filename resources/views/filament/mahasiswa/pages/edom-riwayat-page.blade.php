<x-filament-panels::page>
    <div class="space-y-6">
        <div class="p-6 bg-white rounded-xl shadow-sm">
            <div class="space-y-4">
                <h2 class="text-xl font-semibold text-gray-900">
                    Riwayat Evaluasi Dosen
                </h2>
                <p class="text-gray-600">
                    Berikut adalah daftar evaluasi yang telah Anda isi. Evaluasi dengan status "Draft" masih dapat diubah dan dikumpulkan.
                </p>
            </div>
        </div>

        {{ $this->table }}

        <div class="flex justify-center">
            <a href="{{ route('filament.mahasiswa.pages.pengisian-edom-page') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-primary-600 rounded-lg hover:bg-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-500">
                <x-heroicon-o-arrow-left class="w-4 h-4 mr-2" />
                Kembali ke Pengisian EDOM
            </a>
        </div>
    </div>
</x-filament-panels::page>