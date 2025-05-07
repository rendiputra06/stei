<x-filament-panels::page>
    <x-filament::section>
        <form wire:submit="generate">
            {{ $this->form }}
            
            <div class="mt-4 flex justify-between">
                <x-filament::button
                    type="submit"
                    color="success"
                    icon="heroicon-o-play"
                >
                    {{ __('Generate Status Mahasiswa') }}
                </x-filament::button>
            </div>
        </form>
    </x-filament::section>
</x-filament-panels::page> 