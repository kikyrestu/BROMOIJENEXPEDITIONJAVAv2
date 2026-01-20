@props(['location'])

@php
    // Fetch active banners (Date check + Active check)
    $now = now();
    $banners = \App\Models\Banner::where('is_active', true)
        ->where(function($q) use ($now) {
            $q->whereNull('start_date')->orWhere('start_date', '<=', $now);
        })
        ->where(function($q) use ($now) {
            $q->whereNull('end_date')->orWhere('end_date', '>=', $now);
        })
        ->get()
        ->filter(function($banner) use ($location) {
            // Check if placements array contains the requested location and that specific placement is active
            $placements = $banner->placements ?? [];
            foreach ($placements as $placement) {
                if (($placement['location'] ?? '') === $location && ($placement['is_active'] ?? false)) {
                    $banner->setAttribute('priority', $placement['priority'] ?? 1); // Attach priority for sorting
                    return true;
                }
            }
            return false;
        })
        ->sortBy('priority');
@endphp

@if($banners->isNotEmpty())
    <div class="w-full space-y-4 my-8">
        @foreach($banners as $banner)
            <div class="relative overflow-hidden rounded-xl shadow-sm hover:shadow-md transition group">
                @if($banner->type === 'image')
                    <a href="{{ $banner->cta_url ?? '#' }}" class="block relative aspect-[4/1] md:aspect-[5/1] w-full overflow-hidden bg-gray-100">
                        @if($banner->image_path)
                            <img src="{{ \Illuminate\Support\Facades\Storage::url($banner->image_path) }}" 
                                 alt="{{ $banner->name }}" 
                                 class="w-full h-full object-cover transition duration-700 group-hover:scale-105">
                        @endif

                        {{-- Overlay & Content --}}
                        <div class="absolute inset-0 flex flex-col justify-center px-8 md:px-16"
                             style="background: {{ $banner->overlay_color ?? 'linear-gradient(90deg, rgba(0,0,0,0.6) 0%, rgba(0,0,0,0) 100%)' }}">
                            
                            @if($banner->heading)
                                <h3 class="text-2xl md:text-4xl font-bold mb-2 text-white" 
                                    style="color: {{ $banner->bg_color ? 'white' : 'inherit' }}">
                                    {{ $banner->heading }}
                                </h3>
                            @endif
                            
                            @if($banner->subheading)
                                <p class="text-white/90 text-sm md:text-lg mb-4 max-w-xl">{{ $banner->subheading }}</p>
                            @endif

                            @if($banner->cta_text)
                                <span class="inline-block px-6 py-2 bg-brand-accent text-white font-bold rounded-lg hover:bg-white hover:text-brand-accent transition w-max">
                                    {{ $banner->cta_text }}
                                </span>
                            @endif
                        </div>
                    </a>
                @elseif($banner->type === 'html')
                    <div class="w-full">
                        {!! $banner->html_content !!}
                    </div>
                @endif
            </div>
        @endforeach
    </div>
@endif
