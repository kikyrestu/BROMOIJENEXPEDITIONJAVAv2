<x-filament-panels::page>
    <form wire:submit="save">
        {{ $this->form }}

        <x-filament::actions 
            :actions="$this->getCachedFormActions()" 
            :full-width="$this->hasFullWidthFormActions()"
            class="mt-6"
        />
        
        <x-filament-actions::modals />
    </form>
</x-filament-panels::page>
