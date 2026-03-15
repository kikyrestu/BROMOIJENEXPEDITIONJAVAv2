@php
    $destSeo = $destination->seo ?? new \App\Models\SeoMetadata([
        'meta_title' => $destination->name . ' Tour Packages',
        'meta_description' => \Illuminate\Support\Str::limit(strip_tags($destination->description ?? ''), 160) ?: 'Explore ' . $destination->name . ' with our professional guided tours.',
    ]);
    if (!$destSeo->meta_title) $destSeo->meta_title = $destination->name . ' Tour Packages';
    if (!$destSeo->meta_description) $destSeo->meta_description = 'Explore ' . $destination->name . ' with our professional guided tours.';
@endphp
<x-app-layout :seo="$destSeo">
    @push('structured-data')
    {{-- BreadcrumbList --}}
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "BreadcrumbList",
        "itemListElement": [
            { "@type": "ListItem", "position": 1, "name": "Home", "item": "{{ route('home') }}" },
            { "@type": "ListItem", "position": 2, "name": "Destinations", "item": "{{ route('destinations.index') }}" },
            { "@type": "ListItem", "position": 3, "name": "{{ $destination->name }}" }
        ]
    }
    </script>
    {{-- TouristDestination --}}
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "TouristDestination",
        "name": "{{ $destination->name }}",
        "description": "{{ $destSeo->meta_description }}",
        "url": "{{ url()->current() }}",
        @if($destination->thumbnail_path)"image": "{{ asset('storage/' . $destination->thumbnail_path) }}",@endif
        "touristType": ["Adventure tourism", "Eco tourism", "Nature tourism"],
        "containedInPlace": {
            "@type": "Country",
            "name": "Indonesia"
        }
    }
    </script>
    @endpush

    {{-- Hero Section --}}
    <section class="relative h-[60vh] min-h-[400px] flex items-center justify-center overflow-hidden">
        {{-- Background Image --}}
        @php
            $heroImage = $destination->thumbnail_path;
            if (!empty($heroImage) && !str_starts_with($heroImage, 'http')) {
                $heroImage = \Illuminate\Support\Facades\Storage::url($heroImage);
            } elseif (empty($heroImage)) {
                $heroImage = 'https://images.unsplash.com/photo-1588668214407-6ea9a6d8c272?w=1600';
            }
        @endphp
        <div class="absolute inset-0">
            <img src="{{ $heroImage }}" alt="{{ $destination->name }}" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-black/20"></div>
        </div>
        
        {{-- Content --}}
        <div class="relative z-10 text-center text-white px-4">
            <nav class="flex items-center justify-center gap-2 text-sm text-white/70 mb-4">
                <a href="{{ route('home') }}" class="hover:text-white transition">Home</a>
                <span>/</span>
                <a href="{{ route('destinations.index') }}" class="hover:text-white transition">Destinations</a>
                <span>/</span>
                <span class="text-white">{{ $destination->name }}</span>
            </nav>
            <h1 class="text-4xl md:text-6xl font-bold mb-4 font-hand tracking-wide">{{ $destination->name }}</h1>
            @if($destination->description)
            <p class="text-lg md:text-xl text-white/80 max-w-2xl mx-auto">{{ $destination->description }}</p>
            @endif
        </div>
    </section>

    {{-- Packages Section --}}
    @if($destination->packages->count() > 0)
    <section class="py-16 bg-white">
        <div class="w-full max-w-screen-2xl mx-auto px-6 md:px-12 lg:px-16">
            <div class="text-center mb-12">
                <span class="inline-block px-4 py-1 bg-brand-primary/10 text-brand-primary text-sm font-bold rounded-full mb-3">Available Tours</span>
                <h2 class="text-3xl md:text-4xl font-bold text-slate-800">
                    Explore <span class="text-brand-primary font-hand italic">{{ $destination->name }}</span> Packages
                </h2>
                <p class="text-slate-600 mt-3 max-w-xl mx-auto">Choose from our carefully curated tour packages to experience the best of {{ $destination->name }}.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($destination->packages as $package)
                <div class="bg-white rounded-2xl p-4 shadow-md border border-slate-100 hover:shadow-xl transition-all duration-300 h-full flex flex-col group">
                    
                    {{-- Image Wrapper --}}
                    <div class="relative rounded-xl overflow-hidden aspect-[16/9] group-hover:opacity-95 transition-opacity">
                        @php
                            $thumb = $package->thumbnail;
                            if(!empty($thumb) && !str_starts_with($thumb, 'http')) {
                                $thumb = \Illuminate\Support\Facades\Storage::url($thumb);
                            } elseif(empty($thumb)) {
                                $thumb = 'https://placehold.co/800x600?text='.urlencode($package->name);
                            }
                        @endphp
                        <img src="{{ $thumb }}" alt="{{ $package->name }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                        
                        {{-- Badges --}}
                        @if($package->is_featured)
                        <div class="absolute top-2 left-2 flex flex-col gap-1">
                            <span class="bg-brand-primary text-white text-[9px] font-bold px-2 py-0.5 rounded">Featured</span>
                        </div>
                        @endif

                        {{-- Wishlist Heart --}}
                        <button class="absolute top-2 right-2 w-7 h-7 bg-white/80 backdrop-blur-sm rounded-full flex items-center justify-center text-slate-400 hover:text-red-500 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                            </svg>
                        </button>

                        {{-- Price Tag --}}
                        @if($package->price)
                        <div class="absolute bottom-2 left-2 bg-brand-accent text-white font-bold px-3 py-1 rounded text-xs shadow-md">
                            IDR {{ number_format($package->price, 0, ',', '.') }}
                        </div>
                        @endif
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
                            <span class="text-xs text-slate-400 font-medium">({{ rand(5, 25) }} Reviews)</span>
                        </div>

                        {{-- Title --}}
                        <h3 class="text-lg font-bold text-brand-dark leading-snug mb-3 line-clamp-2 group-hover:text-brand-primary transition-colors">
                            <a href="{{ route('packages.show', $package->slug) }}">{{ $package->name }}</a>
                        </h3>

                        {{-- Meta Info --}}
                        <div class="flex items-center gap-4 text-xs text-slate-500 font-medium mb-4">
                            <div class="flex items-center gap-1.5">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 text-brand-primary">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                                </svg>
                                {{ $package->destination->name ?? $destination->name }}
                            </div>
                            @if($package->duration_days)
                            <div class="flex items-center gap-1.5">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 text-brand-primary">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                                {{ $package->duration_days }} Days
                            </div>
                            @endif
                        </div>
                    </div>

                    {{-- Footer Actions --}}
                    <div class="pt-3 border-t border-slate-100 flex items-center justify-between mt-auto">
                        <a href="{{ route('packages.show', $package->slug) }}" class="text-brand-primary hover:text-brand-accent font-bold text-sm flex items-center gap-1 group-hover:gap-2 transition-all">
                            View Details
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                        <button class="text-brand-primary hover:text-brand-accent transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7.217 10.907a2.25 2.25 0 1 0 0 2.186m0-2.186c.18.324.283.696.283 1.093s-.103.77-.283 1.093m0-2.186 9.566-5.314m-9.566 7.5 9.566 5.314m0 0a2.25 2.25 0 1 0 3.935 2.186 2.25 2.25 0 0 0-3.935-2.186Zm0-12.814a2.25 2.25 0 1 0 3.935-2.186 2.25 2.25 0 0 0-3.935 2.186Z" />
                            </svg>
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @else
    <section class="py-16 bg-white">
        <div class="w-full max-w-screen-2xl mx-auto px-6 md:px-12 lg:px-16 text-center">
            <div class="bg-slate-50 rounded-2xl p-12 max-w-2xl mx-auto">
                <svg class="w-16 h-16 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                <h3 class="text-xl font-bold text-slate-800 mb-2">No Packages Available Yet</h3>
                <p class="text-slate-600">We're currently preparing exciting tour packages for {{ $destination->name }}. Check back soon!</p>
            </div>
        </div>
    </section>
    @endif

    {{-- Related Articles Section --}}
    @if($blogs->count() > 0)
    <section class="py-16 bg-slate-50">
        <div class="w-full max-w-screen-2xl mx-auto px-6 md:px-12 lg:px-16">
            <div class="text-center mb-12">
                <span class="inline-block px-4 py-1 bg-brand-accent/10 text-brand-accent text-sm font-bold rounded-full mb-3">Travel Guide</span>
                <h2 class="text-3xl md:text-4xl font-bold text-slate-800">
                    Articles About <span class="text-brand-primary font-hand italic">{{ $destination->name }}</span>
                </h2>
                <p class="text-slate-600 mt-3 max-w-xl mx-auto">Read our travel guides and tips for your adventure to {{ $destination->name }}.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($blogs as $blog)
                <a href="{{ route('blogs.show', $blog->slug) }}" class="group block">
                    <div class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-lg transition-all duration-300">
                        {{-- Image --}}
                        <div class="relative aspect-[16/9] overflow-hidden">
                            @php
                                $blogThumb = $blog->thumbnail_path ?? $blog->cover_image;
                                if (!empty($blogThumb) && !str_starts_with($blogThumb, 'http')) {
                                    $blogThumb = \Illuminate\Support\Facades\Storage::url($blogThumb);
                                } elseif (empty($blogThumb)) {
                                    $blogThumb = 'https://placehold.co/800x450?text=Blog';
                                }
                            @endphp
                            <img src="{{ $blogThumb }}" alt="{{ $blog->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        </div>
                        
                        {{-- Content --}}
                        <div class="p-6">
                            <div class="flex items-center gap-2 text-xs text-slate-500 mb-3">
                                <span>{{ $blog->created_at->format('M d, Y') }}</span>
                                @if($blog->reading_time)
                                <span>•</span>
                                <span>{{ $blog->reading_time }} min read</span>
                                @endif
                            </div>
                            <h3 class="text-lg font-bold text-slate-800 group-hover:text-brand-primary transition line-clamp-2">{{ $blog->title }}</h3>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>

            <div class="text-center mt-10">
                <a href="{{ route('blogs.index') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-white border-2 border-brand-primary text-brand-primary font-bold rounded-full hover:bg-brand-primary hover:text-white transition-all">
                    <span>View All Articles</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
            </div>
        </div>
    </section>
    @endif

    {{-- CTA Section --}}
    <section class="py-20 bg-gradient-to-br from-brand-primary to-brand-dark text-white">
        <div class="w-full max-w-screen-2xl mx-auto px-6 md:px-12 lg:px-16 text-center">
            <h2 class="text-3xl md:text-4xl font-bold mb-4">Ready to Explore {{ $destination->name }}?</h2>
            <p class="text-white/80 text-lg mb-8 max-w-xl mx-auto">Contact us now to plan your unforgettable adventure. Our team is ready to help you create the perfect trip.</p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="#book" class="px-8 py-4 bg-white text-brand-primary font-bold rounded-full hover:bg-brand-accent hover:text-white transition-all shadow-lg">
                    Book Now
                </a>
                <a href="{{ route('packages.index') }}" class="px-8 py-4 border-2 border-white text-white font-bold rounded-full hover:bg-white hover:text-brand-primary transition-all">
                    View All Packages
                </a>
            </div>
        </div>
    </section>
</x-app-layout>
