<?php
    $seo = new \App\Models\SeoMetadata([
        'meta_title' => 'Destinations — East Java & Bali',
        'meta_description' => 'Explore Mount Bromo, Ijen Crater, Tumpak Sewu Waterfall & Bali with local expert guides. Stunning volcanoes, waterfalls & beaches — plan your adventure now.',
    ]);
?>

<x-app-layout :seo="$seo">
    {{-- Hero Section --}}
    <div class="relative pt-24 pb-12 md:pt-32 md:pb-20 lg:pt-48 lg:pb-32 overflow-hidden">
        <div class="absolute inset-0 bg-brand-dark">
            <img src="https://placehold.co/1920x600?text=Explore+Destinations" class="w-full h-full object-cover opacity-40">
            <div class="absolute inset-0 bg-gradient-to-t from-brand-dark to-transparent"></div>
        </div>
        
        <div class="container mx-auto px-6 md:px-12 lg:px-20 relative z-10 text-center">
            <span class="inline-block bg-white/10 backdrop-blur-md text-white border border-white/20 font-hand text-xl px-4 py-1 rounded-full mb-4 transform -rotate-1">
                Discover The Wonders
            </span>
            <h1 class="text-4xl md:text-6xl font-extrabold text-white tracking-tight leading-tight mb-6">
                Our <span class="text-brand-primary font-hand italic">Destinations</span>
            </h1>
            <p class="text-slate-300 text-lg max-w-2xl mx-auto">
                Discover the extraordinary landscapes and raw beauty of East Java through our carefully selected destinations.
            </p>
        </div>
    </div>

    {{-- Destinations Grid Section --}}
    <section class="py-16 md:py-24 bg-white relative">
        <div class="container mx-auto px-6 md:px-12 lg:px-20">
            
            @if($destinations->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 md:gap-8 mb-12">
                    @foreach($destinations as $destination)
                        <div class="group flex flex-col h-full bg-white rounded-3xl overflow-hidden shadow-[0_2px_20px_rgba(0,0,0,0.05)] border border-slate-100 hover:shadow-xl transition-all duration-300">
                            {{-- Image --}}
                            <div class="relative aspect-[4/3] overflow-hidden">
                                <a href="{{ route('destinations.show', $destination->slug) }}">
                                    <img src="{{ Storage::url($destination->image_path) }}" 
                                         alt="{{ $destination->name }}" loading="lazy"
                                         class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                                </a>
                                
                                {{-- Package Count Badge --}}
                                <div class="absolute top-4 right-4">
                                     <span class="bg-white/90 backdrop-blur-sm text-brand-dark text-xs font-bold px-3 py-1.5 rounded-full shadow-sm flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 text-brand-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                        </svg>
                                        {{ $destination->packages->count() }} Packages
                                     </span>
                                </div>
                            </div>

                            {{-- Content --}}
                            <div class="p-8 flex-1 flex flex-col items-center text-center">
                                <h3 class="text-2xl font-extrabold text-brand-dark mb-4 group-hover:text-brand-primary transition-colors leading-tight">
                                    <a href="{{ route('destinations.show', $destination->slug) }}">
                                        {{ $destination->name }}
                                    </a>
                                </h3>

                                <p class="text-slate-500 text-sm mb-6 leading-relaxed line-clamp-3">
                                    {{ Str::limit(strip_tags($destination->description), 120) }}
                                </p>

                                <div class="mt-auto pt-6 border-t border-slate-50 w-full flex justify-center">
                                    <a href="{{ route('destinations.show', $destination->slug) }}" class="inline-flex items-center gap-2 text-brand-primary font-bold text-sm group/link hover:text-brand-dark transition-colors">
                                        Explore Destination
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 transition-transform group-hover/link:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-20 bg-slate-50 rounded-3xl border border-slate-100">
                    <h3 class="text-lg font-bold text-slate-700">No destinations found</h3>
                    <p class="text-slate-500">We are currently adding new destinations. Check back soon!</p>
                </div>
            @endif

        </div>
    </section>
</x-app-layout>
