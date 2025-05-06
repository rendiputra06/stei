<div class="p-4 bg-white rounded-lg shadow-sm">
    <h2 class="text-lg font-semibold mb-4">Generate Status Mahasiswa</h2>
    
    {{ $this->form }}
    
    <div class="mt-4">
        <button type="button" wire:click="generate" wire:loading.attr="disabled" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
            <span wire:loading.remove wire:target="generate">Generate Status Mahasiswa</span>
            <span wire:loading wire:target="generate">Generating...</span>
        </button>
    </div>
    
    @if($isGenerating)
        <div class="mt-4 p-2 bg-blue-50 text-blue-700 rounded">
            Menghasilkan data status mahasiswa...
        </div>
    @endif
    
    @if($generateCount > 0)
        <div class="mt-4 p-2 bg-green-50 text-green-700 rounded">
            Berhasil membuat {{ $generateCount }} data status mahasiswa baru.
        </div>
    @endif
</div> 