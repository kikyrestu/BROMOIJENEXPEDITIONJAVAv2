@props(['packages'])

<section id="packages" class="py-24 bg-slate-950 relative">
    <div class="container-custom">
        <div class="flex justify-between items-end mb-12">
            <div>
                <h2 class="text-3xl md:text-5xl font-bold tracking-tight text-white mb-4">Trending <span class="text-brand-accent">Packages</span></h2>
                <p class="text-slate-400">Choose your perfect adventure.</p>
            </div>
            
            {{-- Swiper Navigation Buttons --}}
            <div class="flex gap-4">
                <button class="swiper-button-prev-custom w-12 h-12 rounded-full border border-white/10 flex items-center justify-center text-white hover:bg-brand-accent hover:text-slate-900 transition">
                    &larr;
                </button>
                <button class="swiper-button-next-custom w-12 h-12 rounded-full border border-white/10 flex items-center justify-center text-white hover:bg-brand-accent hover:text-slate-900 transition">
                    &rarr;
                </button>
            </div>
        </div>

        <div class="swiper package-swiper overflow-visible">
            <div class="swiper-wrapper">
                @foreach($packages as $package)
                    <div class="swiper-slide h-auto">
                        <div class="bg-slate-900 rounded-3xl overflow-hidden border border-white/5 h-full flex flex-col group hover:border-brand-accent/50 transition duration-500">
                            {{-- Image --}}
                            <div class="relative h-64 overflow-hidden">
                                <img src="{{ $package->destination->thumbnail_path ? asset('storage/'.$package->destination->thumbnail_path) : 'https://via.placeholder.com/800x600' }}"
                                     alt="{{ $package->name }}" loading="lazy"
                                     class="w-full h-full object-cover transition duration-700 group-hover:scale-110">
                                @if($package->is_exclusive)
                                    <span class="absolute top-4 right-4 bg-brand-accent text-slate-900 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider">Exclusive</span>
                                @endif
                            </div>
                            
                            {{-- Content --}}
                            <div class="p-8 flex flex-col flex-1">
                                <div class="flex items-center gap-2 text-xs text-slate-400 mb-3 uppercase tracking-wider font-medium">
                                    <span class="text-brand-accent">{{ $package->destination->name }}</span>
                                    <span>&bull;</span>
                                    <span>{{ $package->duration_days }} Days</span>
                                </div>
                                <h3 class="text-2xl font-bold text-white mb-4 group-hover:text-brand-accent transition">{{ $package->name }}</h3>
                                
                                <div class="mt-auto flex items-center justify-between pt-6 border-t border-white/5">
                                    <div>
                                        <span class="text-xs text-slate-500 block">Start from</span>
                                        <span class="text-lg font-bold text-white">IDR {{ number_format($package->price_start_from, 0, ',', '.') }}</span>
                                    </div>
                                    <a href="{{ route('packages.show', $package->slug) }}" class="w-11 h-11 rounded-full bg-white/5 flex items-center justify-center text-white group-hover:bg-brand-accent group-hover:text-slate-900 transition">
                                        &nearr;
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        new Swiper('.package-swiper', {
            modules: [SwiperModules.Navigation, SwiperModules.Autoplay],
            slidesPerView: 1,
            spaceBetween: 24,
            loop: true,
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
            },
            navigation: {
                nextEl: '.swiper-button-next-custom',
                prevEl: '.swiper-button-prev-custom',
            },
            breakpoints: {
                640: {
                    slidesPerView: 2,
                },
                1024: {
                    slidesPerView: 3,
                },
            }
        });
    });
</script>
