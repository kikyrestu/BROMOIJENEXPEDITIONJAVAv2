@php
    $pkgSeo = $package->seo ?? new \App\Models\SeoMetadata([
        'meta_title' => $package->name,
        'meta_description' => $package->short_description ?: \Illuminate\Support\Str::limit(strip_tags($package->long_description ?? ''), 160),
        'og_type' => 'product',
    ]);
    if (!$pkgSeo->meta_title) $pkgSeo->meta_title = $package->name;
    if (!$pkgSeo->meta_description) $pkgSeo->meta_description = $package->short_description ?: \Illuminate\Support\Str::limit(strip_tags($package->long_description ?? ''), 160);
    $pkgSeo->og_type = 'product';
@endphp
<x-app-layout :seo="$pkgSeo">

    @push('structured-data')
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "TouristTrip",
        "name": "{{ $package->name }}",
        "description": "{{ $pkgSeo->meta_description }}",
        "url": "{{ url()->current() }}",
        @if($package->thumbnail)"image": "{{ asset('storage/' . $package->thumbnail) }}",@endif
        "touristType": "Adventure",
        "provider": {
            "@type": "TravelAgency",
            "name": "Bromo Ijen Expedition Java"
        },
        @if($package->price_start_from)
        "offers": {
            "@type": "Offer",
            "priceCurrency": "USD",
            "price": "{{ $package->price_start_from }}",
            "availability": "https://schema.org/InStock"
        },
        @endif
        @if($package->rating)
        "aggregateRating": {
            "@type": "AggregateRating",
            "ratingValue": "{{ $package->rating }}",
            "reviewCount": "{{ $package->review_count ?? 1 }}"
        },
        @endif
        "itinerary": {
            "@type": "ItemList",
            "numberOfItems": {{ $package->duration_days ?? 1 }},
            "name": "{{ $package->duration_days ?? 1 }} Days {{ $package->duration_nights ?? 0 }} Nights"
        }
    }
    </script>
    @endpush

    <div class="pt-32 pb-40 container mx-auto px-6 md:px-12 lg:px-20 font-sans text-slate-600">
        
        {{-- TOP HEADER: Title & Share --}}
        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-6 mb-8">
            <div>
                 <h1 class="text-3xl md:text-5xl font-extrabold text-brand-dark leading-tight mb-2">
                    {{ $package->name }}
                </h1>
                <div class="flex items-center gap-2 text-sm text-slate-500">
                     <span class="text-brand-accent font-bold">({{ $package->review_count ?? 0 }} Review)</span>
                     <div class="flex text-brand-accent text-xs">
                        @for($i = 0; $i < 5; $i++)
                            <i class="fas fa-star {{ $i < round($package->rating) ? '' : 'text-slate-300' }}"></i>
                        @endfor
                     </div>
                     <span class="mx-2">|</span>
                     <span class="flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-brand-primary" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
                        {{ $package->location ?? 'East Java, Indonesia' }}
                     </span>
                </div>
            </div>
            
            <button class="px-6 py-2.5 rounded-lg border border-slate-300 text-brand-dark font-bold hover:bg-brand-primary hover:text-white hover:border-brand-primary transition-all flex items-center gap-2">
                Share
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" /></svg>
            </button>
        </div>

        {{-- INFO BAR (With Green Price Button) --}}
        <div class="bg-white border-t border-b border-slate-100 py-6 mb-12">
            <div class="flex flex-col lg:flex-row items-center justify-between gap-6">
                <div class="flex flex-wrap items-center gap-4 md:gap-8 lg:gap-16 w-full lg:w-auto">
                    {{-- Location --}}
                    <div class="flex gap-4 items-center">
                        <div class="w-10 h-10 rounded-full bg-brand-accent/10 flex items-center justify-center text-brand-accent">
                             <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                        </div>
                        <div>
                            <span class="block text-xs font-bold text-brand-dark">Location</span>
                            <span class="text-sm text-slate-500">{{ $package->destination->name ?? 'East Java' }}</span>
                        </div>
                    </div>
                    {{-- Activities Type --}}
                    <div class="flex gap-4 items-center">
                        <div class="w-10 h-10 rounded-full bg-brand-accent/10 flex items-center justify-center text-brand-accent">
                             <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                        </div>
                        <div>
                            <span class="block text-xs font-bold text-brand-dark">Activities Type</span>
                            <span class="text-sm text-slate-500">{{ $package->category ?? 'Adventure' }}</span>
                        </div>
                    </div>
                    {{-- Activate Day --}}
                    <div class="flex gap-4 items-center">
                        <div class="w-10 h-10 rounded-full bg-brand-accent/10 flex items-center justify-center text-brand-accent">
                             <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>
                        <div>
                            <span class="block text-xs font-bold text-brand-dark">Duration</span>
                            <span class="text-sm text-slate-500">{{ $package->duration_days }}D {{ $package->duration_nights }}N</span>
                        </div>
                    </div>
                </div>

                <div class="w-full lg:w-auto">
                    <button class="w-full lg:w-auto bg-brand-primary text-white font-bold py-3 px-8 rounded-lg text-lg shadow-lg hover:bg-green-600 transition-colors">
                        IDR {{ number_format($package->price_start_from/1000, 0) }}k <span class="text-xs font-normal opacity-80">/Per Person</span>
                    </button>
                </div>
            </div>
        </div>

        {{-- MAIN LAYOUT: Content Left, Sidebar Right --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12 text-slate-500">
            
            {{-- LEFT CONTENT --}}
            <div class="lg:col-span-2 space-y-10">
                
                {{-- TOP GALLERY (Hidden in screenshot but needed for context) --}}
                {{-- TOP GALLERY --}}
                {{-- TOP GALLERY --}}
                @php
                    $mainImage = $package->thumbnail ? asset('storage/'.$package->thumbnail) : 'https://placehold.co/1200x800?text=No+Thumbnail';
                    $gallery = $package->gallery ?? [];
                    // Kumpulkan semua image untuk lightbox (Main + Gallery)
                    $allImages = [$mainImage];
                    foreach($gallery as $g) {
                        $allImages[] = asset('storage/'.$g);
                    }
                    $sideImages = array_slice($gallery, 0, 2); 
                @endphp

                <div x-data="{ 
                        lightboxOpen: false, 
                        images: {{ json_encode($allImages) }},
                        activeIndex: 0,
                        openLightbox(index) {
                            this.activeIndex = index;
                            this.lightboxOpen = true;
                            document.body.style.overflow = 'hidden'; // Disable scroll
                        },
                        closeLightbox() {
                            this.lightboxOpen = false;
                            document.body.style.overflow = ''; // Enable scroll
                        },
                        next() {
                            this.activeIndex = (this.activeIndex + 1) % this.images.length;
                        },
                        prev() {
                            this.activeIndex = (this.activeIndex - 1 + this.images.length) % this.images.length;
                        }
                    }"
                    @keydown.escape.window="closeLightbox()">

                    {{-- GRID WRAPPER --}}
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 h-[250px] md:h-[400px] rounded-2xl overflow-hidden mb-8">
                         {{-- Main Image (Thumbnail) - Index 0 --}}
                         <div class="md:col-span-3 h-full relative group" @click="openLightbox(0)">
                            <img src="{{ $mainImage }}" 
                                 alt="{{ $package->name }}"
                                 class="w-full h-full object-cover hover:scale-105 transition-transform duration-700 cursor-pointer">
                            <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-colors pointer-events-none"></div>
                            <div class="absolute top-4 right-4 bg-white/90 p-2 rounded-full opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-brand-dark" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" /></svg>
                            </div>
                        </div>
    
                        {{-- Side Images (From Gallery) - Index 1 onwards --}}
                        <div class="hidden md:flex flex-col gap-4 h-full">
                            @foreach($sideImages as $index => $img)
                                <div class="flex-1 relative overflow-hidden group" @click="openLightbox({{ $index + 1 }})">
                                    <img src="{{ asset('storage/'.$img) }}" 
                                         class="w-full h-full object-cover hover:scale-110 transition-transform duration-500 cursor-pointer">
                                    
                                    {{-- Jika ini gambar kedua dan masih ada lagi sisa gallery, tampilkan overlay +N --}}
                                    @if($index === 1 && count($gallery) > 2)
                                        <div class="absolute inset-0 bg-black/50 flex items-center justify-center cursor-pointer group-hover:bg-black/40 transition-colors">
                                            <span class="text-white font-bold text-xl">+{{ count($gallery) - 2 }} Photos</span>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
    
                            {{-- Fallback jika gallery kosong --}}
                            @if(count($sideImages) === 0)
                                <div class="flex-1 relative bg-slate-100 flex items-center justify-center text-slate-400 text-xs text-center p-4">
                                    No Gallery Images
                                </div>
                                <div class="flex-1 relative bg-slate-100 flex items-center justify-center text-slate-400 text-xs text-center p-4">
                                    Add images in Admin
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- LIGHTBOX MODAL (Teleported to Body to fix Z-Index/Stacking Context) --}}
                    <template x-teleport="body">
                        <div x-show="lightboxOpen" 
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0"
                             x-transition:enter-end="opacity-100"
                             x-transition:leave="transition ease-in duration-200"
                             x-transition:leave-start="opacity-100"
                             x-transition:leave-end="opacity-0"
                             class="fixed inset-0 z-[9999] bg-black/95 flex flex-col items-center justify-center p-4"
                             style="display: none;">
                            
                            {{-- Toolbar --}}
                            <div class="absolute top-6 right-6 flex items-center gap-4 z-50">
                                 <div class="text-white/80 text-sm font-bold">
                                    <span x-text="activeIndex + 1"></span> / <span x-text="images.length"></span>
                                 </div>
                                 <button @click="closeLightbox()" class="text-white hover:text-brand-primary transition-colors p-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                 </button>
                            </div>
    
                            {{-- Main Image Stage --}}
                            <div class="relative w-full max-w-6xl h-full max-h-[80vh] flex items-center justify-center" @click.outside="closeLightbox()">
                                 
                                 {{-- Prev Button --}}
                                 <button @click.stop="prev()" class="absolute left-0 md:-left-12 top-1/2 -translate-y-1/2 p-3 text-white/50 hover:text-white transition-colors hover:bg-white/10 rounded-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                                 </button>
    
                                 {{-- Image --}}
                                 <img :src="images[activeIndex]" 
                                      class="max-w-full max-h-full object-contain rounded shadow-2xl select-none"
                                      x-transition:enter="transition ease-out duration-300"
                                      x-transition:enter-start="opacity-0 scale-95"
                                      x-transition:enter-end="opacity-100 scale-100">
    
                                 {{-- Next Button --}}
                                 <button @click.stop="next()" class="absolute right-0 md:-right-12 top-1/2 -translate-y-1/2 p-3 text-white/50 hover:text-white transition-colors hover:bg-white/10 rounded-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                                 </button>
                            </div>
    
                            {{-- Thumbnails Strip --}}
                            <div class="mt-8 flex gap-2 overflow-x-auto w-full max-w-4xl justify-center px-4 pb-2">
                                <template x-for="(img, idx) in images" :key="idx">
                                    <div @click.stop="activeIndex = idx" 
                                         class="w-16 h-16 md:w-20 md:h-20 flex-shrink-0 cursor-pointer rounded-lg overflow-hidden border-2 transition-all"
                                         :class="activeIndex === idx ? 'border-brand-primary ring-2 ring-brand-primary/50' : 'border-transparent opacity-60 hover:opacity-100'">
                                        <img :src="img" class="w-full h-full object-cover">
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>

                </div>

                {{-- Overview --}}
                <section>
                    <h2 class="text-2xl font-extrabold text-brand-dark mb-4">Overview</h2>
                    <div class="leading-relaxed prose prose-slate max-w-none">
                         {!! $package->long_description ?? $package->short_description ?? 'No description available for this package.' !!}
                    </div>
                </section>

                {{-- Highlight List (Green Checks) --}}
                <section>
                    <h2 class="text-2xl font-extrabold text-brand-dark mb-4">Highlight List</h2>
                    @if(count($package->highlights ?? []) > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($package->highlights as $highlight)
                                <div class="flex items-center gap-3">
                                    <div class="w-5 h-5 rounded-full border border-brand-primary flex items-center justify-center text-brand-primary text-xs"><i class="fas fa-check"></i></div>
                                    <span>{{ $highlight }}</span>
                                </div>
                            @endforeach
                        </div>
                    @else
                         <div class="text-sm text-slate-400 italic">No specific highlights listed for this tour.</div>
                    @endif
                </section>

                {{-- Tour Amenities (Included / Excluded) --}}
                <section>
                    <h2 class="text-2xl font-extrabold text-brand-dark mb-4">Tour Amenities</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 border-t border-b border-slate-100 py-6">
                        {{-- Included --}}
                        <div class="space-y-3">
                            <h3 class="text-sm font-bold text-green-600 uppercase tracking-wide mb-3">✓ Included</h3>
                            @if($package->inclusions)
                                @php
                                    // Strip HTML tags and split by newlines or "- " prefix
                                    $inclusionText = strip_tags($package->inclusions);
                                    $inclusionItems = preg_split('/\n|(?<=^|\n)\s*-\s*/m', $inclusionText);
                                    $inclusionItems = array_filter(array_map('trim', $inclusionItems));
                                @endphp
                                <ul class="space-y-2">
                                    @foreach($inclusionItems as $item)
                                        @if(!empty($item))
                                            <li class="flex items-center gap-3 text-sm text-slate-600">
                                                <span class="text-green-500 font-extrabold text-lg">✓</span>
                                                {{ ltrim($item, '- ') }}
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            @else
                                <div class="text-sm text-slate-400 italic">No inclusions specified.</div>
                            @endif
                        </div>
                        {{-- Excluded --}}
                        <div class="space-y-3">
                            <h3 class="text-sm font-bold text-red-600 uppercase tracking-wide mb-3">✕ Excluded</h3>
                             @if($package->exclusions)
                                @php
                                    $exclusionText = strip_tags($package->exclusions);
                                    $exclusionItems = preg_split('/\n|(?<=^|\n)\s*-\s*/m', $exclusionText);
                                    $exclusionItems = array_filter(array_map('trim', $exclusionItems));
                                @endphp
                                <ul class="space-y-2">
                                    @foreach($exclusionItems as $item)
                                        @if(!empty($item))
                                            <li class="flex items-center gap-3 text-sm text-slate-600">
                                                <span class="text-red-500 font-extrabold text-lg">✕</span>
                                                {{ ltrim($item, '- ') }}
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            @else
                                <div class="text-sm text-slate-400 italic">No exclusions specified.</div>
                            @endif
                        </div>
                    </div>
                </section>

                {{-- Two Images (Visual) --}}
                {{-- Two Images (Visual - From Gallery) --}}
                @php
                    // Ambil gambar ke-3 dan ke-4 dari gallery untuk section ini
                    // Jika gallery kurang dari 4, ambil random atau reuse yang awal
                    $amenityImages = count($gallery) > 2 ? array_slice($gallery, 2, 2) : array_slice($gallery, 0, 2);
                @endphp
                
                @if(count($amenityImages) > 0)
                    <div class="grid grid-cols-2 gap-6">
                        @foreach($amenityImages as $img)
                            <div class="h-48 md:h-64 rounded-2xl overflow-hidden shadow-sm">
                                <img src="{{ asset('storage/'.$img) }}" class="w-full h-full object-cover hover:scale-110 transition-transform duration-500">
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- Tour Plan (Accordion) --}}
                <section x-data="{ activeDay: 0 }">
                    <h2 class="text-2xl font-extrabold text-brand-dark mb-6">Tour Plan</h2>
                    <div class="space-y-4">
                        @foreach($package->itinerary ?? [] as $index => $item)
                            <div class="border border-slate-100 rounded-xl bg-white shadow-sm">
                                <button @click="activeDay = (activeDay === {{ $index }} ? null : {{ $index }})" 
                                        class="w-full flex items-center justify-between p-5 text-left">
                                    <div class="flex items-center gap-2">
                                        <span class="text-brand-primary font-bold">Day {{ $index + 1 }}</span>
                                        <span class="font-bold text-brand-dark">{{ $item['title'] ?? 'Activity' }}</span>
                                    </div>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-400 transition-transform" :class="activeDay === {{ $index }} ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                </button>
                                <div x-show="activeDay === {{ $index }}" x-collapse class="px-5 pb-5 pt-0 text-sm text-slate-500">
                                    @if(isset($item['description']))
                                        <p class="mb-2">{{ $item['description'] }}</p>
                                    @endif

                                    @if(isset($item['activities']) && is_array($item['activities']))
                                        <ul class="space-y-2">
                                            @foreach($item['activities'] as $activity)
                                                <li class="flex gap-3">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-brand-primary mt-1.5 flex-shrink-0"></span>
                                                    <span>{{ $activity }}</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>

                {{-- FAQs Section (Dynamic) --}}
                @if(count($package->faqs ?? []) > 0)
                <section x-data="{ activeFaq: null }" class="mb-20">
                    <h2 class="text-2xl font-extrabold text-brand-dark mb-6">Frequently Asked Questions</h2>
                    <div class="space-y-3">
                        @foreach($package->faqs as $index => $faq)
                            <div class="border border-slate-100 rounded-xl bg-white shadow-sm overflow-hidden">
                                <button @click="activeFaq = (activeFaq === {{ $index }} ? null : {{ $index }})" 
                                        class="w-full flex items-center justify-between p-4 text-left hover:bg-slate-50 transition-colors">
                                    <span class="font-bold text-brand-dark">{{ $faq['question'] ?? 'Question' }}</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-400 transition-transform" :class="activeFaq === {{ $index }} ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                </button>
                                <div x-show="activeFaq === {{ $index }}" x-collapse class="px-4 pb-4 pt-0 text-sm text-slate-500 bg-slate-50/50">
                                    {{ $faq['answer'] ?? '' }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
                @endif

            </div>

             {{-- SIDEBAR --}}
            <div class="hidden lg:block relative col-span-1">
                <div class="sticky top-28 space-y-6">
                    
                    {{-- Booking Card (Interactive) --}}
                    @php
                        // Fetch Phone from Settings (DB) to ensure realtime update
                        $rawPhone = \App\Models\Setting::where('key', 'provider_phone')->value('value') 
                                    ?? \App\Models\Setting::where('key', 'whatsapp_number')->value('value') 
                                    ?? config('settings.whatsapp_number', '6281234567890');

                        // Sanitize Phone for WhatsApp URL (628...)
                        $phone = preg_replace('/[^0-9]/', '', $rawPhone);
                        if (str_starts_with($phone, '0')) {
                            $phone = '62' . substr($phone, 1);
                        }
                    @endphp
                    <div class="border border-slate-200 rounded-2xl p-8 bg-white shadow-sm"
                         x-data="{
                            date: '',
                            time: '',
                            addonBooking: false,
                            addonPerson: false,
                            price: {{ $package->price_start_from }},
                            phone: '{{ $phone }}',
                            
                            get formattedDate() {
                                if(!this.date) return '';
                                const d = new Date(this.date);
                                return d.toLocaleDateString('en-US', { day: 'numeric', month: 'short', year: 'numeric' });
                            },

                            get waLink() {
                                let msg = `Hi, I am interested in the *{{ $package->name }}* package.`;
                                msg += `\nLink: {{ url('/packages/'.$package->slug) }}`;
                                
                                if(this.date) msg += `\n📅 Date: ${this.formattedDate}`;
                                if(this.time) msg += `\n⏰ Time: ${this.time}`;
                                
                                if(this.addonBooking) msg += `\n+ Extra Per Booking`;
                                if(this.addonPerson) msg += `\n+ Extra Per Person`;

                                return `https://wa.me/${this.phone}?text=${encodeURIComponent(msg)}`;
                            }
                         }">
                        <h3 class="text-xl font-extrabold text-brand-dark mb-6">Book This Tour</h3>
                        
                        {{-- Form Fields --}}
                        <div class="space-y-4 mb-6">
                            {{-- From Date --}}
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-2">From:</label>
                                <div class="relative">
                                    <input type="date" 
                                           x-model="date"
                                           min="{{ date('Y-m-d') }}"
                                           class="w-full border border-slate-200 rounded-lg py-3 px-4 text-sm focus:outline-none focus:border-brand-primary bg-slate-50 uppercase placeholder-slate-400">
                                </div>
                            </div>
                            
                            {{-- Time --}}
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-2">Time:</label>
                                <select x-model="time" class="w-full border border-slate-200 rounded-lg py-3 px-4 text-sm focus:outline-none focus:border-brand-primary bg-slate-50 text-slate-500">
                                    <option value="">Select Time</option>
                                    <option>00:00 (Midnight)</option>
                                    <option>03:00 AM</option>
                                    <option>08:00 AM</option>
                                    <option>01:00 PM</option>
                                </select>
                            </div>

                            {{-- Tickets (Show info based on Date selection) --}}
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-2">Availability:</label>
                                <div class="text-sm font-bold py-3 px-4 rounded-lg border transition-colors"
                                     :class="date ? 'bg-green-50 text-green-600 border-green-200' : 'bg-slate-50 text-slate-400 border-slate-200'">
                                    <span x-text="date ? 'Running on ' + formattedDate : 'Please, Select Date First'"></span>
                                </div>
                            </div>

                            {{-- Add Extra - Only show if package has extras data --}}
                            {{-- Currently no backend support for extras, section hidden --}}
                        </div>

                        {{-- Total & Button --}}
                        <div class="border-t border-slate-100 pt-6">
                            <div class="flex justify-between items-center mb-2 text-sm">
                                <span>Adult:</span>
                                <span class="font-bold">IDR {{ number_format($package->price_start_from/1000, 0) }}k</span>
                            </div>
                             <div class="flex justify-between items-center mb-6 text-lg font-extrabold text-brand-dark">
                                <span>Total:</span>
                                <span>IDR {{ number_format($package->price_start_from/1000, 0) }}k</span>
                            </div>
                            
                             <a :href="waLink" 
                               target="_blank"
                               class="block w-full py-4 bg-brand-primary text-white font-bold rounded-full text-center hover:bg-green-600 transition-colors shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                                Book Now ->
                            </a>
                        </div>
                    </div>

                    {{-- Map Widget --}}
                    {{-- Map Widget --}}
                    @if(!empty($package->map_embed_url))
                        <div class="rounded-2xl overflow-hidden relative h-64 border border-slate-200 shadow-sm">
                            <iframe 
                                src="{{ $package->map_embed_url }}" 
                                width="100%" 
                                height="100%" 
                                style="border:0;" 
                                allowfullscreen="" 
                                loading="lazy" 
                                referrerpolicy="no-referrer-when-downgrade">
                            </iframe>
                        </div>
                    @else
                        <div class="rounded-2xl overflow-hidden relative h-64 border border-slate-200 shadow-sm cursor-pointer group bg-slate-100 flex items-center justify-center">
                            <span class="text-sm text-slate-400 font-bold">No Map Data Available</span>
                        </div>
                    @endif

                    {{-- DEBUG DATA AUDIT --}}
                    <script>
                        document.addEventListener('DOMContentLoaded', () => {
                            console.group('📦 Package Data Audit (From Backend)');
                            console.log('ID:', {{ $package->id }});
                            console.log('Name:', @json($package->name));
                            console.log('Slug:', @json($package->slug));
                            console.log('Map Embed URL:', @json($package->map_embed_url));
                            console.log('Price:', {{ $package->price_start_from }});
                            console.log('Duration:', @json($package->duration_days) + ' days');
                            console.log('Inclusions:', @json($package->inclusions));
                            console.log('Exclusions:', @json($package->exclusions));
                            console.log('Itinerary Data:', @json($package->itinerary));
                            console.log('Full Object:', @json($package));
                            
                            // Gallery Check
                            const gallery = @json($package->gallery);
                            console.group('🖼️ Gallery Audit');
                            if (Array.isArray(gallery) && gallery.length > 0) {
                                console.info(`✅ Gallery has ${gallery.length} images.`);
                                gallery.forEach((img, idx) => console.log(`[${idx}]`, img));
                            } else {
                                console.warn('⚠️ Gallery array is EMPTY or NULL.');
                            }
                            console.groupEnd();

                            if (!@json($package->map_embed_url)) {
                                console.warn('⚠️ Map Embed URL is EMPTY directly from Backend!');
                            } else {
                                console.info('✅ Map Embed URL found:', @json($package->map_embed_url));
                            }
                            console.groupEnd();
                        });
                    </script>

                </div>
            </div>

        </div>
    </div>

    {{-- Mobile Sticky CTA Bar --}}
    @php
        $mobilePhone = $phone ?? '6281234567890';
        $mobileWaLink = "https://wa.me/{$mobilePhone}?text=" . urlencode("Hi, I am interested in the *{$package->name}* package.\nLink: " . url('/packages/'.$package->slug));
    @endphp
    <div class="lg:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-slate-200 p-4 z-50 shadow-[0_-4px_20px_rgba(0,0,0,0.1)]">
        <div class="flex items-center justify-between gap-4">
            <div>
                <span class="text-xs text-slate-500">Start from</span>
                <div class="text-xl font-extrabold text-brand-dark">IDR {{ number_format($package->price_start_from/1000, 0) }}k</div>
            </div>
            <a href="{{ $mobileWaLink }}" 
               target="_blank"
               class="bg-brand-primary text-white font-bold py-3 px-6 rounded-full shadow-lg hover:bg-green-600 transition-colors flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                Book Now
            </a>
        </div>
    </div>
</x-app-layout>
