<x-app-layout>
    {{-- Hero Section --}}
    @php $heroImg = \App\Models\Setting::where('key', 'hero_image_gallery')->value('value'); @endphp
    <div class="relative pt-24 pb-12 md:pt-32 md:pb-20 lg:pt-48 lg:pb-32 overflow-hidden">
        <div class="absolute inset-0 bg-brand-dark">
            <img src="{{ $heroImg ? Storage::disk('public')->url($heroImg) : asset('images/heroes/hero-1_optimized.webp') }}" alt="Bromo Ijen Expedition Gallery" class="w-full h-full object-cover opacity-40">
            <div class="absolute inset-0 bg-gradient-to-t from-brand-dark to-transparent"></div>
        </div>
        
        <div class="container mx-auto px-6 md:px-12 lg:px-20 relative z-10 text-center">
            <span class="inline-block bg-white/10 backdrop-blur-md text-white border border-white/20 font-hand text-xl px-4 py-1 rounded-full mb-4 transform -rotate-1">
                Captured Moments
            </span>
            <h1 class="text-4xl md:text-6xl font-extrabold text-white tracking-tight leading-tight mb-6">
                Our <span class="text-brand-primary font-hand italic">Gallery</span>
            </h1>
            <p class="text-slate-300 text-lg max-w-2xl mx-auto">
                Explore breathtaking moments captured during our expeditions
            </p>
        </div>
    </div>

    {{-- Gallery Grid with Infinite Scroll --}}
    <section class="py-16 bg-white">
        <div class="w-full max-w-screen-2xl mx-auto px-6 md:px-12 lg:px-16"
             x-data="infiniteGallery()"
             x-init="loadInitial()">

            {{-- Category Filter Tabs --}}
            @if(isset($categories) && $categories->isNotEmpty())
            <div class="flex flex-wrap items-center justify-center gap-2 mb-10">
                <button type="button"
                    @click="filterCategory('all')"
                    :class="activeCategory === 'all' ? 'bg-brand-primary text-white shadow-lg shadow-brand-primary/30' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'"
                    class="px-5 py-2 rounded-full text-sm font-bold transition-all duration-300 cursor-pointer capitalize">
                    All
                </button>
                @foreach($categories as $cat)
                    <button type="button"
                        @click="filterCategory('{{ $cat }}')"
                        :class="activeCategory === '{{ $cat }}' ? 'bg-brand-primary text-white shadow-lg shadow-brand-primary/30' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'"
                        class="px-5 py-2 rounded-full text-sm font-bold transition-all duration-300 cursor-pointer capitalize">
                        {{ ucfirst($cat) }}
                    </button>
                @endforeach
            </div>
            @endif
            
            {{-- Masonry Grid --}}
            <div class="columns-1 sm:columns-2 md:columns-3 lg:columns-4 gap-4 space-y-4" x-ref="grid">
                {{-- Initial server-rendered images --}}
                @foreach($images as $image)
                <div class="break-inside-avoid group relative overflow-hidden rounded-xl shadow-sm hover:shadow-xl transition-all duration-300"
                     x-show="activeCategory === 'all' || activeCategory === '{{ $image->category }}'"
                     x-transition>
                    <img src="{{ $image->display_url }}" 
                         alt="{{ $image->alt_text ?? $image->title }}" loading="lazy"
                         class="w-full object-cover transition-transform duration-500 group-hover:scale-110"
                         loading="lazy">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end p-4">
                        <p class="text-white font-medium text-sm">{{ $image->alt_text ?? $image->title }}</p>
                    </div>
                    {{-- Lightbox Trigger (shows original full-size) --}}
                    <button @click="openLightbox('{{ $image->original_url }}', '{{ addslashes($image->alt_text ?? $image->title) }}')" 
                            class="absolute top-3 right-3 w-8 h-8 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center text-white opacity-0 group-hover:opacity-100 transition-all hover:bg-white/40">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/></svg>
                    </button>
                </div>
                @endforeach
                
                {{-- Dynamically loaded images --}}
                <template x-for="image in images" :key="image.id">
                    <div class="break-inside-avoid group relative overflow-hidden rounded-xl shadow-sm hover:shadow-xl transition-all duration-300"
                         x-show="activeCategory === 'all' || activeCategory === image.category"
                         x-transition>
                        <img :src="image.url" 
                             :alt="image.alt" 
                             class="w-full object-cover transition-transform duration-500 group-hover:scale-110"
                             loading="lazy">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end p-4">
                            <p class="text-white font-medium text-sm" x-text="image.alt"></p>
                        </div>
                        <button @click="openLightbox(image.originalUrl || image.url, image.alt)" 
                                class="absolute top-3 right-3 w-8 h-8 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center text-white opacity-0 group-hover:opacity-100 transition-all hover:bg-white/40">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/></svg>
                        </button>
                    </div>
                </template>
            </div>
            
            {{-- Loading Indicator --}}
            <div x-show="loading" class="flex justify-center py-8">
                <div class="flex items-center gap-2 text-slate-500">
                    <svg class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span>Loading more...</span>
                </div>
            </div>
            
            {{-- End of Gallery --}}
            <div x-show="!hasMore && !loading" class="text-center py-8 text-slate-400">
                <p>You've reached the end of the gallery</p>
            </div>
            
            {{-- Scroll Trigger --}}
            <div x-intersect:enter="loadMore" class="h-10"></div>
        </div>
    </section>

    {{-- Lightbox Modal --}}
    <div x-data="{ show: false, src: '', alt: '' }" 
         x-on:open-lightbox.window="show = true; src = $event.detail.src; alt = $event.detail.alt"
         x-show="show"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @keydown.escape.window="show = false"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/90"
         style="display: none;">
        <button @click="show = false" class="absolute top-4 right-4 text-white hover:text-brand-accent transition">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
        <img :src="src" :alt="alt" class="max-w-full max-h-[90vh] object-contain rounded-lg shadow-2xl">
        <p class="absolute bottom-4 left-1/2 -translate-x-1/2 text-white text-sm bg-black/50 px-4 py-2 rounded-full" x-text="alt"></p>
    </div>

    <script>
        function infiniteGallery() {
            return {
                images: [],
                page: {{ $images->currentPage() + 1 }},
                hasMore: {{ $images->hasMorePages() ? 'true' : 'false' }},
                loading: false,
                activeCategory: 'all',
                
                loadInitial() {
                    // Initial images are server-rendered
                },
                
                filterCategory(cat) {
                    this.activeCategory = cat;
                },
                
                openLightbox(src, alt) {
                    this.$dispatch('open-lightbox', { src, alt });
                },
                
                async loadMore() {
                    if (this.loading || !this.hasMore) return;
                    
                    this.loading = true;
                    
                    try {
                        const response = await fetch(`/api/gallery?page=${this.page}`);
                        const data = await response.json();
                        
                        this.images = [...this.images, ...data.data];
                        this.hasMore = data.has_more;
                        this.page++;
                    } catch (error) {
                        console.error('Failed to load more images:', error);
                    } finally {
                        this.loading = false;
                    }
                }
            }
        }
    </script>
</x-app-layout>
