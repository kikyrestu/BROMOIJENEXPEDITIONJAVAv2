@php
    $statePath = $component->getContainer()->getStatePath();
@endphp

<div x-data="{
    statePath: '{{ $statePath }}',
    imageUrl: null,
    spots: [],
    
    init() {
        // Watch for changes in the hybrid fields
        this.$watch('$wire.get(statePath + \'.image_file\')', () => this.refreshImage());
        this.$watch('$wire.get(statePath + \'.image_url\')', () => this.refreshImage());
        this.$watch('$wire.get(statePath + \'.image_source\')', () => this.refreshImage());

        // Watch for changes in the spots array
        this.$watch('$wire.get(statePath + \'.spots\')', (value) => {
            this.spots = value ? Object.values(value) : [];
        });

        // Initial Load
        this.refreshImage();
        
        const initialSpots = $wire.get(this.statePath + '.spots');
        if (initialSpots) this.spots = Object.values(initialSpots);
    },

    refreshImage() {
        const source = $wire.get(this.statePath + '.image_source') || 'upload';
        
        if (source === 'url') {
            this.imageUrl = $wire.get(this.statePath + '.image_url');
        } else {
            const file = $wire.get(this.statePath + '.image_file');
            if (!file) {
                this.imageUrl = null;
                return;
            }
            // Handle array or string
            let path = Array.isArray(file) ? Object.values(file)[0] : file;
            if (path && typeof path === 'string') {
                 this.imageUrl = '/storage/' + path; 
            } else {
                 this.imageUrl = null;
            }
        }
    },

    updateSpotPosition(uuid, x, y) {
        // Find the spot/row key by UUID if using standard Repeater
        // Filament Repeaters use UUIDs as keys in the backend array
        // We need to find the key in the Livewire data
        
        const spotsObject = $wire.get(this.statePath + '.spots');
        
        // Find key by matching UUID or iterating if spotsObject is keyed by UUID
        // The 'spots' local array is simplified. We need to write back to the correct path.
        
        for (const [key, spot] of Object.entries(spotsObject)) {
             // We can't easily rely on 'spot.id' unless we generated it. 
             // But we can match by index if the local array order matches. 
             // Best way: Store the Key in the local spots array on init.
        }
    },
    
    // SIMPLIFIED APPROACH:
    // We bind directly to the indices if we treat spots as an ordered list. 
    // BUT Filament repeaters use UUID keys.
    // So we must iterate the keys.
    
    get spotsWithKeys() {
        const raw = $wire.get(this.statePath + '.spots') || {};
        return Object.entries(raw).map(([key, data]) => ({ ...data, key }));
    }

}" class="rounded-xl border border-gray-200 overflow-hidden bg-gray-50 p-4 shadow-sm"
    x-init="init()"
>
    
    <div class="mb-2 flex items-center justify-between">
        <h3 class="text-sm font-medium text-gray-700">Interactive Preview</h3>
        <p class="text-xs text-gray-500">Drag pins to position. Click to add not supported yet (use Add button below).</p>
    </div>

    <template x-if="imageUrl">
        <div class="relative w-full rounded-lg overflow-hidden shadow-inner border border-gray-300 bg-gray-200 group" style="background-image: repeating-linear-gradient(45deg, #e5e7eb 25%, transparent 25%, transparent 75%, #e5e7eb 75%, #e5e7eb), repeating-linear-gradient(45deg, #e5e7eb 25%, #f9fafb 25%, #f9fafb 75%, #e5e7eb 75%, #e5e7eb); background-position: 0 0, 10px 10px; background-size: 20px 20px;">
            <img :src="imageUrl" alt="Preview" class="w-full h-auto block select-none pointer-events-none">
            
            {{-- Pins --}}
            <template x-for="spot in spotsWithKeys" :key="spot.key">
                <div class="absolute w-6 h-6 -ml-3 -mt-3 cursor-move z-10 flex items-center justify-center transition-transform hover:scale-125 hover:z-20"
                     :style="`left: ${spot.x}%; top: ${spot.y}%;`"
                     :title="spot.tooltip_override || 'Hotspot'"
                     draggable="true"
                     @dragstart="$event.dataTransfer.effectAllowed = 'move'; $event.dataTransfer.setData('text/plain', spot.key)"
                     @dragend="
                        const rect = $el.parentElement.getBoundingClientRect();
                        const clientX = $event.clientX;
                        const clientY = $event.clientY;
                        
                        // Calculate percentage
                        let x = ((clientX - rect.left) / rect.width) * 100;
                        let y = ((clientY - rect.top) / rect.height) * 100;
                        
                        // Clamp
                        x = Math.max(0, Math.min(100, x));
                        y = Math.max(0, Math.min(100, y));
                        
                        // Update Livewire
                        $wire.set(statePath + '.spots.' + spot.key + '.x', x.toFixed(2));
                        $wire.set(statePath + '.spots.' + spot.key + '.y', y.toFixed(2));
                     "
                >
                    {{-- Pin Icon --}}
                    <div class="relative">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-4 w-4 bg-red-600 border-2 border-white shadow-md"></span>
                    </div>
                </div>
            </template>

        </div>
    </template>

    <template x-if="!imageUrl">
        <div class="flex items-center justify-center h-48 bg-gray-100 rounded-lg border-2 border-dashed border-gray-300 text-gray-400">
            <span>Upload an image first</span>
        </div>
    </template>
</div>
