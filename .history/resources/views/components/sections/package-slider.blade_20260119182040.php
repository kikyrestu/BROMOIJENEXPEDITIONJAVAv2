@props(['packages', 'data' => []])
@php
    use App\Helpers\WhatsAppLinkGenerator;
    
    $badge = $data['badge'] ?? 'Popular Tours';
    $title = $data['title'] ?? 'Feature <span class="text-brand-primary font-hand italic">Packages</span>';
@endphp

<section class="py-16 bg-white relative font-sans" x-data="{
    init() {
        let checkSwiper = setInterval(() => {
            if (typeof Swiper !== 'undefined') {
                clearInterval(checkSwiper);
                new Swiper(this.$refs.swiper, {
                    modules: [SwiperModules.Navigation, SwiperModules.Pagination],
                    slidesPerView: 'auto',
                    spaceBetween: 24,
                    grabCursor: true,
                    navigation: { 
                        nextEl: '.custom-next', 
                        prevEl: '.custom-prev' 
                    },
                });
            }
        }, 100);
    }
}">
    <div class="container mx-auto px-6 md:px-12 lg:px-20">
        
        {{-- Section Header --}}
        <div class="flex flex-col md:flex-row items-end justify-between mb-10 gap-4">
            <div class="text-left">
            <div class="text-left">
                <span data-live="badge_text" class="inline-block bg-orange-100 text-orange-500 font-hand text-xl px-4 py-1 rounded-full mb-2 transform -rotate-1">
                    {{ $badge }}
                </span>
                <h2 class="text-4xl md:text-5xl font-extrabold text-brand-dark tracking-tight">
                   @if(!empty($data['title_prefix']) || !empty($data['title_suffix']))
                        <span data-live="title_prefix">{{ $data['title_prefix'] ?? '' }}</span>
                        <span data-live="title_suffix" class="text-brand-primary font-hand italic">
                            {{ $data['title_suffix'] ?? '' }}
                        </span>
                    @else
                        <span data-live="title">{!! $title !!}</span>
                    @endif
                </h2>
                @if(isset($data['description']) && $data['description'])
                    <p data-live="description" class="mt-4 text-slate-500 max-w-2xl">{{ $data['description'] }}</p>
                @endif
            </div>

            {{-- Navigation Buttons (Top Right) --}}
            <div class="flex items-center gap-3">
                <button class="custom-prev w-12 h-12 rounded-full border border-slate-200 text-slate-500 hover:bg-brand-primary hover:text-white hover:border-brand-primary transition-all flex items-center justify-center group cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 group-hover:-translate-x-0.5 transition-transform">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                    </svg>
                </button>
                <button class="custom-next w-12 h-12 rounded-full border border-slate-200 text-slate-500 hover:bg-brand-primary hover:text-white hover:border-brand-primary transition-all flex items-center justify-center group cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 group-hover:translate-x-0.5 transition-transform">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                    </svg>
                </button>
            </div>
        </div>

        <div class="swiper !overflow-visible" x-ref="swiper">
            <div class="swiper-wrapper py-4 pb-12">
                @foreach($packages as $package)
                    <div class="swiper-slide !w-[320px] md:!w-[380px] h-auto">
                        
                        {{-- Card Container --}}
                        <div class="bg-white rounded-2xl p-4 shadow-[0_2px_20px_rgba(0,0,0,0.05)] border border-slate-100 hover:shadow-xl transition-all duration-300 h-full flex flex-col group">
                            
                            {{-- Image Wrapper (Smaller aspect ratio) --}}
                            <div class="relative rounded-xl overflow-hidden aspect-[16/9] group-hover:opacity-95 transition-opacity">
                                <img src="https://placehold.co/800x600?text={{ urlencode($package->name) }}" 
                                     alt="{{ $package->name }}" 
                                     class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                                
                                {{-- Top Badges --}}
                                <div class="absolute top-2 left-2 flex flex-col gap-1">
                                    <span class="bg-brand-accent text-white text-[9px] font-bold px-2 py-0.5 rounded">
                                        -20%
                                    </span>
                                    <span class="bg-brand-primary text-white text-[9px] font-bold px-2 py-0.5 rounded">
                                        Hot
                                    </span>
                                </div>

                                {{-- Wishlist Heart (Top Right) --}}
                                <button class="absolute top-2 right-2 w-7 h-7 bg-white/80 backdrop-blur-sm rounded-full flex items-center justify-center text-slate-400 hover:text-red-500 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                                    </svg>
                                </button>

                                {{-- Price Tag (Bottom Left) --}}
                                <div class="absolute bottom-2 left-2 bg-brand-accent text-white font-bold px-3 py-1 rounded text-xs shadow-md">
                                    IDR {{ number_format($package->price_start_from/1000, 0) }}k
                                </div>
                            </div>

                            {{-- Card Body --}}
                            <div class="pt-5 pb-2 flex-1 flex flex-col">
                                {{-- Rating --}}
                                <div class="flex items-center gap-1 mb-2">
                                    <div class="flex text-yellow-400 text-xs">
                                        @for($i=0; $i<5; $i++)
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
                                                <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z" clip-rule="evenodd" />
                                            </svg>
                                        @endfor
                                    </div>
                                    <span class="text-xs text-slate-400 font-medium">(12 Reviews)</span>
                                </div>

                                {{-- Title --}}
                                <h3 class="text-lg font-bold text-brand-dark leading-snug mb-3 line-clamp-2 group-hover:text-brand-primary transition-colors">
                                    <a href="{{ route('packages.show', $package->slug) }}">
                                        {{ $package->name }}
                                    </a>
                                </h3>

                                {{-- Meta Info --}}
                                <div class="flex items-center gap-4 text-xs text-slate-500 font-medium mb-4">
                                    <div class="flex items-center gap-1.5">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 text-brand-primary">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                                        </svg>
                                        {{ $package->destination->name }}
                                    </div>
                                    <div class="flex items-center gap-1.5">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 text-brand-primary">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                        </svg>
                                        {{ $package->duration_days }} Days
                                    </div>
                                </div>
                            </div>

                            {{-- Footer Actions --}}
                            <div class="pt-3 border-t border-slate-100 flex items-center justify-between mt-auto">
                                {{-- Like --}}
                                <button class="text-brand-primary hover:text-brand-accent transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                                    </svg>
                                </button>

                                {{-- Book Now Button --}}
                                <a href="{{ route('packages.show', $package->slug) }}" class="flex items-center gap-2 pl-4 pr-1 py-1.5 rounded-full border border-brand-primary text-brand-dark font-bold text-xs hover:bg-brand-primary hover:text-white transition-all group/btn">
                                    Book Now
                                    <div class="w-6 h-6 rounded-full bg-brand-primary text-white flex items-center justify-center group-hover/btn:bg-white group-hover/btn:text-brand-primary transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3 h-3">
                                            <path fill-rule="evenodd" d="M3 10a.75.75 0 0 1 .75-.75h10.638L10.23 5.29a.75.75 0 1 1 1.04-1.08l5.5 5.25a.75.75 0 0 1 0 1.08l-5.5 5.25a.75.75 0 1 1-1.04-1.08l4.158-3.96H3.75A.75.75 0 0 1 3 10Z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </a>
                            </div>

                        </div>
                    </div>
                @endforeach
            </div>
            
            {{-- Pagination Dots --}}
            <div class="swiper-pagination !bottom-0"></div>
        </div>
    </div>
</section>

