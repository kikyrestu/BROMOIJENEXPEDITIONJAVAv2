@props(['data' => []])

@php
    $badge = $data['badge'] ?? 'Our Memories';
    
    // Defaults
    $defaultPrefix = 'Capture The';
    $defaultSuffix = 'Moments';
    
    $prefix = $data['title_prefix'] ?? null;
    $suffix = $data['title_suffix'] ?? null;
    
    if(empty($prefix) && empty($suffix)) {
        $prefix = $defaultPrefix;
        $suffix = $defaultSuffix;
    }

    $description = $data['description'] ?? 'Explore the beauty of East Java through our lens. From the sunrise of Bromo to the blue fire of Ijen.';
    
    // Images Logic
    // Default static images if no data provided
    $images = $data['images'] ?? [];
    if (empty($images)) {
        $images = [
            ['image' => 'https://placehold.co/800x800?text=Bromo+Sunrise', 'size' => 'large', 'caption' => 'Golden Sunrise'],
            ['image' => 'https://placehold.co/400x400?text=Jeep+Ride', 'size' => 'small', 'caption' => 'Jeep Adventure'],
            ['image' => 'https://placehold.co/400x400?text=Ijen+Crater', 'size' => 'small', 'caption' => 'Blue Fire'],
            ['image' => 'https://placehold.co/800x800?text=Savana', 'size' => 'tall', 'caption' => 'Savana Hills'],
            ['image' => 'https://placehold.co/400x400?text=People', 'size' => 'small', 'caption' => 'Happy Travelers'],
            ['image' => 'https://placehold.co/800x400?text=Milky+Way', 'size' => 'wide', 'caption' => 'Milky Way']
        ];
    }
@endphp

<section class="py-16 md:py-24 bg-white relative font-sans overflow-hidden">
    
    <div class="container mx-auto px-6 md:px-12 lg:px-20 relative z-10">
        
        {{-- Section Header --}}
        <div class="text-center max-w-3xl mx-auto mb-12">
            <span data-live="badge_text" class="inline-block bg-orange-100 text-orange-500 font-hand text-xl px-4 py-1 rounded-full mb-3 transform -rotate-1">
                {{ $badge }}
            </span>
            <h2 class="text-4xl md:text-5xl font-extrabold text-brand-dark tracking-tight leading-tight mb-4">
                <span data-live="title_prefix">{{ $prefix }}</span>
                <span data-live="title_suffix" class="text-brand-primary font-hand italic">
                    {{ $suffix }}
                </span>
            </h2>
            <p data-live="description" class="text-slate-500 text-lg leading-relaxed">
                {{ $description }}
            </p>
        </div>

        {{-- Gallery Grid --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 auto-rows-[200px]">
            
            @foreach($images as $img)
                @php 
                    $src = $img['image'] ?? null;
                    if (empty($src)) {
                        $imgUrl = 'https://placehold.co/800x800?text=No+Image';
                    } elseif (filter_var($src, FILTER_VALIDATE_URL)) {
                        $imgUrl = $src;
                    } else {
                        $imgUrl = \Illuminate\Support\Facades\Storage::url($src);
                    }
                    // Quick fix for placeholder URLs which are valid URLs
                    if (str_contains($img['image'], 'placehold.co')) { $imgUrl = $img['image']; }
                    
                    $size = $img['size'] ?? 'small';
                    $sizeClass = match ($size) {
                        'large' => 'col-span-2 row-span-2',
                        'wide' => 'col-span-2 row-span-1',
                        'tall' => 'col-span-1 row-span-2',
                        default => 'col-span-1 row-span-1', // small
                    };
                @endphp

                <div class="{{ $sizeClass }} group relative rounded-3xl overflow-hidden cursor-pointer">
                    <img src="{{ $imgUrl }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-colors"></div>
                    
                    @if(($img['size'] ?? 'small') === 'large')
                         <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <div class="w-12 h-12 bg-white/20 backdrop-blur-md rounded-full flex items-center justify-center text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                                </svg>
                            </div>
                        </div>
                    @endif

                    @if(!empty($img['caption']))
                        <div class="absolute bottom-4 left-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                             <span class="text-white font-bold text-shadow-sm">{{ $img['caption'] }}</span>
                        </div>
                    @endif
                </div>
            @endforeach

        </div>

         {{-- View More Text --}}
         <div class="text-center mt-10">
            <a href="#" class="inline-flex flex-col items-center gap-2 text-slate-400 font-bold hover:text-brand-primary transition-colors group">
                <span class="tracking-widest uppercase text-xs">View More</span>
                <div class="w-8 h-8 rounded-full border border-slate-200 flex items-center justify-center group-hover:border-brand-primary group-hover:bg-brand-primary group-hover:text-white transition-all animate-bounce">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                    </svg>
                </div>
            </a>
         </div>

    </div>
</section>
