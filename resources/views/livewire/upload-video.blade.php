<x-mary-modal  wire:model="modal2" title="Upload Video">
    <x-slot:actions>
        <x-mary-button label="Cancel" @click="$wire.modal = false" />
        <x-mary-button label="Confirm" class="btn-primary" />
    </x-slot:actions>

</x-mary-modal>


