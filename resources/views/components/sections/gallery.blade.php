@props(['data' => []])

@php
    $badge = $data['badge'] ?? 'Our Memories';
    
    $defaultPrefix = 'Capture The';
    $defaultSuffix = 'Moments';
    
    $prefix = $data['title_prefix'] ?? null;
    $suffix = $data['title_suffix'] ?? null;
    
    if(empty($prefix) && empty($suffix)) {
        $prefix = $defaultPrefix;
        $suffix = $defaultSuffix;
    }

    $description = $data['description'] ?? 'Explore the beauty of East Java through our lens. From the sunrise of Bromo to the blue fire of Ijen.';
    
    // Pull from Gallery model only
    $galleryItems = \App\Models\Gallery::orderBy('sort_order')->take(6)->get();

    // Layout sizes for masonry grid
    $sizes = ['large', 'small', 'small', 'tall', 'small', 'wide'];
@endphp

<section class="py-16 md:py-24 bg-white relative font-sans overflow-hidden" x-data="{ showLightbox: false, activeSrc: '', activeAlt: '' }">
    
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

        @if($galleryItems->isNotEmpty())
            {{-- Gallery Grid --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 auto-rows-[200px]">
                @foreach($galleryItems as $index => $item)
                    @php
                        $size = $sizes[$index] ?? 'small';
                        $sizeClass = match ($size) {
                            'large' => 'col-span-2 row-span-2',
                            'wide' => 'col-span-2 row-span-1',
                            'tall' => 'col-span-1 row-span-2',
                            default => 'col-span-1 row-span-1',
                        };
                    @endphp
                    <div class="{{ $sizeClass }} group relative rounded-3xl overflow-hidden cursor-pointer"
                         @click="showLightbox = true; activeSrc = '{{ $item->original_url }}'; activeAlt = '{{ addslashes($item->alt_text ?? $item->title) }}'">
                        <img src="{{ $item->display_url }}" 
                             alt="{{ $item->alt_text ?? $item->title }}"
                             loading="lazy"
                             class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-colors"></div>
                        
                        <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <div class="w-12 h-12 bg-white/20 backdrop-blur-md rounded-full flex items-center justify-center text-white hover:bg-white/40 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                                </svg>
                            </div>
                        </div>

                        @if($item->title)
                            <div class="absolute bottom-4 left-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none">
                                 <span class="text-white font-bold text-shadow-sm">{{ $item->title }}</span>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            {{-- Empty State --}}
            <div class="text-center py-16 text-slate-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 mx-auto mb-4 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0 0 22.5 18.75V5.25A2.25 2.25 0 0 0 20.25 3H3.75A2.25 2.25 0 0 0 1.5 5.25v13.5A2.25 2.25 0 0 0 3.75 21Z" />
                </svg>
                <p class="text-lg font-medium">No gallery images yet</p>
                <p class="text-sm mt-1">Add photos via the admin panel</p>
            </div>
        @endif

         {{-- View More Text --}}
         @if($galleryItems->isNotEmpty())
         <div class="text-center mt-10">
            <a href="{{ route('gallery.index') }}" class="inline-flex flex-col items-center gap-2 text-slate-400 font-bold hover:text-brand-primary transition-colors group">
                <span class="tracking-widest uppercase text-xs">View More</span>
                <div class="w-8 h-8 rounded-full border border-slate-200 flex items-center justify-center group-hover:border-brand-primary group-hover:bg-brand-primary group-hover:text-white transition-all animate-bounce">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                    </svg>
                </div>
            </a>
         </div>
         @endif

    </div>

    {{-- Lightbox Modal --}}
    <div x-show="showLightbox" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @keydown.escape.window="showLightbox = false"
         class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/95"
         style="display: none;"
         x-cloak>
        
        <button @click="showLightbox = false" class="absolute top-6 right-6 text-white/50 hover:text-white transition-colors z-[101]">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>

        <div class="relative w-full max-w-6xl max-h-screen flex flex-col items-center justify-center" @click.outside="showLightbox = false">
            <img :src="activeSrc" :alt="activeAlt" class="max-w-full max-h-[85vh] object-contain rounded-lg shadow-2xl">
            <p class="text-white/90 text-lg mt-4 font-medium" x-text="activeAlt"></p>
        </div>
    </div>
</section>
