@props(['data'])

@php
    // Expecting data to have keys from PageResource builder: 
    // - hero_video: { video_url, heading, subheading }
    // - hotspots: { image, spots: [{x, y, destination_id, tooltip_override}] }
    // BUT since these are separate blocks in the builder, this component might receive just ONE block's data.
    // However, the request implies combining them. 
    // OPTION: We will build a 'Hero' component that accepts the video data AND optionally overlays the hotspots if provided.
    // Or we assume this component renders the 'hotspots' block but styled as a Hero if it's the first item.
    
    // Let's implement the 'Hotspots' block logic primarily, but with a flexible container.
    // If the user wants Video + Hotspots, they should ideally be in one block, but I separated them.
    // I will write this as the 'Hotspots' block renderer, assuming the video might be separate or this block has an image background.
    
    $image = $data['image'] ?? null;
    $spots = $data['spots'] ?? [];
@endphp

<section class="relative w-full h-screen overflow-hidden flex items-center justify-center bg-slate-900" 
         x-data="{ activeTooltip: null }">
    
    <!-- Background Image (The Map/Scene) -->
    <!-- Background Image (The Map/Scene) -->
    @php
        $bgImage = null;
        $source = $data['image_source'] ?? 'upload';
        
        if ($source === 'media_library' && !empty($data['media_id'])) {
            $media = \App\Models\Media::find($data['media_id']);
            $bgImage = $media ? $media->url : null;
        } elseif ($source === 'upload' && !empty($data['image_file'])) {
            $bgImage = Storage::disk('public')->url($data['image_file']);
        } elseif ($source === 'url' && !empty($data['image_url'])) {
            $bgImage = $data['image_url'];
        }
    @endphp

    @if($bgImage)
        <div class="absolute inset-0 z-0">
            <img src="{{ $bgImage }}" 
                 alt="Interactive Map" 
                 class="w-full h-full object-cover opacity-60">
            <!-- Overlay Gradient -->
            <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-transparent to-slate-900/50"></div>
        </div>
    @endif

    <!-- Content Container -->
    <div class="relative z-10 w-full h-full container-custom flex flex-col justify-center items-center pointer-events-none">
        
        <!-- Interactive Layer (Pointer Events Re-enabled) -->
        <div class="absolute inset-0 w-full h-full pointer-events-auto">
            @foreach($spots as $index => $spot)
                @php
                    $destName = \App\Models\Destination::find($spot['destination_id'])?->name ?? 'Unknown';
                    $label = $spot['tooltip_override'] ?: $destName;
                @endphp

                <!-- Hotspot Marker -->
                <div class="absolute" 
                     style="left: {{ $spot['x'] }}%; top: {{ $spot['y'] }}%; transform: translate(-50%, -50%);">
                    
                    <button @click="activeTooltip = activeTooltip === {{ $index }} ? null : {{ $index }}"
                            @mouseenter="activeTooltip = {{ $index }}"
                            class="relative group focus:outline-none">
                        
                        <!-- Pulse Effect -->
                        <span class="absolute inline-flex h-full w-full rounded-full bg-brand-accent opacity-75 animate-ping"></span>
                        
                        <!-- Dot -->
                        <span class="relative inline-flex rounded-full h-4 w-4 bg-brand-accent border-2 border-white shadow-[0_0_15px_rgba(56,189,248,0.8)] transition-transform duration-300 hover:scale-125"></span>

                        <!-- Tooltip Card -->
                        <div x-show="activeTooltip === {{ $index }}"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 translate-y-2"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 translate-y-0"
                             x-transition:leave-end="opacity-0 translate-y-2"
                             class="absolute left-1/2 bottom-full mb-4 w-48 -translate-x-1/2 bg-slate-800/90 backdrop-blur-md rounded-xl p-3 border border-white/10 shadow-xl text-center z-50 pointer-events-none"
                             style="display: none;">
                            
                            <h4 class="text-sm font-semibold text-white mb-1">{{ $label }}</h4>
                            <span class="text-xs text-brand-accent uppercase tracking-wider font-medium">Explore</span>
                            
                            <!-- Arrow -->
                            <div class="absolute top-full left-1/2 -translate-x-1/2 -mt-1 border-4 border-transparent border-t-slate-800/90"></div>
                        </div>
                    </button>
                </div>
            @endforeach
        </div>

        <!-- Optional Text Overlay (If this block had text fields, or if we render the separate Hero Video block on top) -->
    </div>
</section>
