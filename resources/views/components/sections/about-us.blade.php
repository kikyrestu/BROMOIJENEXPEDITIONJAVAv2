@props(['data' => []])

@php
    $badge = $data['badge'] ?? 'About BromoIjen';
    $title = $data['title'] ?? 'Experience The <span class="text-brand-primary">New Adventure</span> With Us';
    $description = $data['description'] ?? 'We organize private guided trips to Mount Bromo, Ijen Crater, and other exotic destinations in East Java. Our goal is to provide safe, comfortable, and memorable experiences for every traveler.';
    
    // Images
    // Images - Logic moved to home.blade.php to avoid double wrapping
    // Helper for images
    $getImageUrl = function($path, $default) {
        if (empty($path)) return $default;
        if (str_starts_with($path, 'http')) return $path;
        if (str_starts_with($path, '/storage') || str_starts_with($path, 'storage')) return Str::start($path, '/');
        return \Illuminate\Support\Facades\Storage::url($path);
    };

    $mainImage = $getImageUrl($data['main_image'] ?? null, 'https://placehold.co/600x800?text=Adventure');
    $secondaryImage = $getImageUrl($data['secondary_image'] ?? null, 'https://placehold.co/500x500?text=Joy');
    
    // Medium variants for srcset
    $mainRawPath = $data['main_image'] ?? null;
    $mainMedium = ($mainRawPath && !str_starts_with($mainRawPath, 'http')) 
        ? \App\Services\ImageOptimizationService::getMediumUrl($mainRawPath) : null;
    $secRawPath = $data['secondary_image'] ?? null;
    $secMedium = ($secRawPath && !str_starts_with($secRawPath, 'http'))
        ? \App\Services\ImageOptimizationService::getMediumUrl($secRawPath) : null;
    // Features
    $features = $data['features'] ?? [
        [
            'title' => 'Trusted Travel Guide',
            'description' => 'Professional English speaking guides.',
            'icon' => 'guide'
        ],
        [
            'title' => 'Instant Booking',
            'description' => 'Easy and secure online booking.',
            'icon' => 'booking'
        ]
    ];
    
    // Footer Info
    $showCta = $data['show_cta'] ?? true;
    $ctaText = $data['cta_text'] ?? 'Discover More';
    $ctaUrl = $data['cta_url'] ?? route('packages.index');
    
    $showFounder = $data['show_founder'] ?? true;
    $founderName = $data['founder_name'] ?? 'Agus Setiawan';
    $founderRole = $data['founder_role'] ?? 'Founder, BromoIjen';
@endphp

<section id="aboutus" class="py-12 md:py-16 bg-[#f9f9f9] relative font-sans overflow-hidden">
    
    {{-- Decorative Background --}}
    <div class="absolute top-20 left-10 opacity-10 pointer-events-none">
        <svg width="80" height="80" viewBox="0 0 100 100" fill="none" class="text-brand-accent animate-pulse">
            <path d="M10 50 Q 50 10 90 50" stroke="currentColor" stroke-width="2" stroke-dasharray="4 4"/>
        </svg>
    </div>

    <div class="container mx-auto px-6 md:px-12 lg:px-20 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12 items-center">
            
            {{-- LEFT: Image Collage --}}
            <div class="relative w-full max-w-sm mx-auto lg:max-w-none">
                
                {{-- Main Image --}}
                <div class="relative z-10 w-[65%] ml-auto lg:ml-8 aspect-[4/5] rounded-tl-[60px] rounded-br-[30px] overflow-hidden shadow-xl border-4 border-white">
                    <img data-live="image_url" src="{{ $mainMedium ?? $mainImage }}" 
                         @if($mainMedium) srcset="{{ $mainMedium }} 600w, {{ $mainImage }} 1080w" sizes="(max-width: 768px) 65vw, 400px" @endif
                         alt="About Bromo Ijen Expedition" loading="lazy" class="w-full h-full object-cover">
                    
                    {{-- Play Button Overlay --}}
                    <div class="absolute inset-0 flex items-center justify-center">
                        <button class="w-12 h-12 bg-white text-brand-accent rounded-full flex items-center justify-center shadow-lg hover:scale-110 transition-transform cursor-pointer">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 ml-0.5">
                                <path fill-rule="evenodd" d="M4.5 5.653c0-1.426 1.529-2.33 2.779-1.643l11.54 6.348c1.295.712 1.295 2.573 0 3.285L7.28 19.991c-1.25.687-2.779-.217-2.779-1.643V5.653z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Secondary Image --}}
                <div class="absolute -bottom-6 -right-2 md:right-8 lg:right-16 w-[45%] aspect-square bg-white p-2 rounded-tl-[30px] rounded-br-[30px] shadow-lg z-20 hidden md:block border border-slate-100">
                     <img data-live="secondary_image_url" src="{{ $secMedium ?? $secondaryImage }}"
                          @if($secMedium) srcset="{{ $secMedium }} 600w, {{ $secondaryImage }} 1080w" sizes="300px" @endif
                          alt="East Java Adventure Tours" loading="lazy" class="w-full h-full object-cover rounded-tl-[24px] rounded-br-[24px]">
                </div>

                {{-- Decorative Green Bar --}}
                <div class="absolute top-6 right-0 lg:right-8 w-2 h-20 bg-brand-primary rounded-full hidden lg:block"></div>
            </div>

            {{-- RIGHT: Text Content --}}
            <div class="lg:pl-6 mt-8 lg:mt-0 text-center lg:text-left">
                <span data-live="badge" class="inline-block bg-orange-100 text-orange-500 font-hand text-2xl md:text-3xl px-4 py-2 rounded-full mb-3 transform -rotate-2">
                    {{ $badge }}
                </span>
                
                <h2 data-live="title" class="text-4xl md:text-6xl font-extrabold text-brand-dark leading-tight mb-6 tracking-tight">
                    {!! $title !!}
                </h2>
                
                <p data-live="description" class="text-slate-500 text-lg mb-8 leading-relaxed max-w-2xl mx-auto lg:mx-0">
                    {{ $description }}
                </p>

                {{-- Features Grid --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8 text-left">
                    @foreach($features as $feature)
                    <div class="flex items-start gap-4 justify-center lg:justify-start">
                        <div class="w-12 h-12 bg-green-50 text-brand-primary rounded-xl flex items-center justify-center shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 0 1-1.043 3.296 3.745 3.745 0 0 1-3.296 1.043A3.745 3.745 0 0 1 12 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 0 1-3.296-1.043 3.745 3.745 0 0 1-1.043-3.296A3.745 3.745 0 0 1 3 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 0 1 1.043-3.296 3.746 3.746 0 0 1 3.296-1.043A3.746 3.746 0 0 1 12 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 0 1 3.296 1.043 3.746 3.746 0 0 1 1.043 3.296A3.745 3.745 0 0 1 21 12Z" />
                            </svg>
                        </div>
                        <div>
                            <h4 data-live="feature_{{ $loop->index + 1 }}_title" class="font-bold text-brand-dark text-xl mb-1">{{ $feature['title'] }}</h4>
                            <p data-live="feature_{{ $loop->index + 1 }}_text" class="text-slate-400 text-sm">{{ $feature['description'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-6 border-t border-slate-200 pt-6">
                    {{-- CTA Button --}}
                    @if($showCta)
                    <a href="{{ $ctaUrl }}" class="px-6 py-3 bg-brand-accent hover:bg-brand-primary text-white font-bold rounded-full shadow-md hover:shadow-lg transition-all flex items-center gap-2 text-sm group">
                        <span data-live="cta_text">{{ $ctaText }}</span>
                        <div class="w-5 h-5 bg-white text-brand-accent rounded-full flex items-center justify-center group-hover:text-brand-primary">
                             <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-2.5 h-2.5">
                                <path fill-rule="evenodd" d="M3 10a.75.75 0 0 1 .75-.75h10.638L10.23 5.29a.75.75 0 1 1 1.04-1.08l5.5 5.25a.75.75 0 0 1 0 1.08l-5.5 5.25a.75.75 0 1 1-1.04-1.08l4.158-3.96H3.75A.75.75 0 0 1 3 10Z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </a>
                    @endif

                    {{-- Founder Profile --}}
                    @if($showFounder)
                    <div class="flex items-center gap-3">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($founderName) }}&background=random" class="w-10 h-10 rounded-full border-2 border-white shadow-sm">
                        <div class="text-left">
                            <h5 data-live="founder_name" class="font-bold text-brand-dark text-sm">{{ $founderName }}</h5>
                            <p data-live="founder_role" class="text-[11px] text-slate-400">{{ $founderRole }}</p>
                        </div>
                    </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
</section>
