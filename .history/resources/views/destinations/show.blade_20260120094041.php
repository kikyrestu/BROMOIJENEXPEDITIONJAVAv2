<x-app-layout>
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
                <a href="{{ route('packages.show', $package) }}" class="group block">
                    <div class="bg-white rounded-2xl overflow-hidden shadow-md hover:shadow-xl transition-all duration-300 border border-slate-100">
                        {{-- Image --}}
                        <div class="relative aspect-[16/10] overflow-hidden">
                            @php
                                $thumb = $package->thumbnail;
                                if (!empty($thumb) && !str_starts_with($thumb, 'http')) {
                                    $thumb = \Illuminate\Support\Facades\Storage::url($thumb);
                                } elseif (empty($thumb)) {
                                    $thumb = 'https://placehold.co/800x500?text=' . urlencode($package->name);
                                }
                            @endphp
                            <img src="{{ $thumb }}" alt="{{ $package->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            @if($package->duration)
                            <span class="absolute top-4 right-4 bg-black/70 text-white text-xs font-bold px-3 py-1 rounded-full backdrop-blur-sm">
                                {{ $package->duration }}
                            </span>
                            @endif
                        </div>
                        
                        {{-- Content --}}
                        <div class="p-6">
                            <h3 class="text-lg font-bold text-slate-800 group-hover:text-brand-primary transition mb-2">{{ $package->name }}</h3>
                            @if($package->short_description)
                            <p class="text-slate-600 text-sm line-clamp-2 mb-4">{{ $package->short_description }}</p>
                            @endif
                            <div class="flex items-center justify-between">
                                @if($package->price)
                                <div>
                                    <span class="text-xs text-slate-500">Starting from</span>
                                    <p class="text-xl font-bold text-brand-primary">Rp {{ number_format($package->price, 0, ',', '.') }}</p>
                                </div>
                                @endif
                                <span class="text-brand-primary font-bold text-sm flex items-center gap-1 group-hover:gap-2 transition-all">
                                    View Details
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </span>
                            </div>
                        </div>
                    </div>
                </a>
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
