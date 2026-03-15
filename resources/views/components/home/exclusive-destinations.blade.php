@props(['destinations'])

<section class="py-24 bg-slate-900 relative overflow-hidden">
    {{-- Background Glow --}}
    <div class="absolute top-0 right-0 w-96 h-96 bg-brand-accent/5 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>

    <div class="container-custom relative z-10">
        <div class="mb-12 text-center">
            <h2 class="text-3xl md:text-5xl font-bold tracking-tight text-white mb-4">Exclusive <span class="text-brand-accent">Destinations</span></h2>
            <p class="text-slate-400 max-w-xl mx-auto">Discover the hidden gems of East Java, curated for the modern adventurer.</p>
        </div>

        @if($destinations->count() >= 3)
            <div class="grid grid-cols-1 md:grid-cols-12 gap-6 h-auto md:h-[600px]">
                
                {{-- Card 1: Large (Left) - col-span-7 --}}
                @php $first = $destinations[0]; @endphp
                <div class="col-span-1 md:col-span-7 relative group rounded-3xl overflow-hidden h-[400px] md:h-full">
                    <img src="{{ asset('storage/' . $first->thumbnail_path) }}" alt="{{ $first->name }}" loading="lazy" class="w-full h-full object-cover transition duration-700 group-hover:scale-110">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent"></div>
                    
                    {{-- Glassmorphism Overlay --}}
                    <div class="absolute bottom-6 left-6 right-6 p-6 bg-white/10 backdrop-blur-md border border-white/10 rounded-2xl transform translate-y-4 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition duration-500">
                        <h3 class="text-2xl font-bold text-white">{{ $first->name }}</h3>
                        <p class="text-slate-200 text-sm mt-2 line-clamp-2">{{ $first->description }}</p>
                        <a href="#" class="inline-block mt-4 text-brand-accent font-medium hover:text-white transition">Explore &rarr;</a>
                    </div>
                    {{-- Always visible title (fades out on hover) --}}
                    <div class="absolute bottom-8 left-8 group-hover:opacity-0 transition duration-300">
                        <h3 class="text-4xl font-bold text-white tracking-tighter">{{ $first->name }}</h3>
                    </div>
                </div>

                {{-- Stack for Card 2 & 3 --}}
                <div class="col-span-1 md:col-span-5 flex flex-col gap-6 h-full">
                    @foreach($destinations->slice(1, 2) as $dest)
                        <div class="relative group rounded-3xl overflow-hidden flex-1">
                            <img src="{{ asset('storage/' . $dest->thumbnail_path) }}" alt="{{ $dest->name }}" loading="lazy" class="w-full h-full object-cover transition duration-700 group-hover:scale-110">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent"></div>
                            
                            <div class="absolute bottom-4 left-4 right-4 p-4 bg-white/10 backdrop-blur-md border border-white/10 rounded-xl transform translate-y-4 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition duration-500">
                                <h3 class="text-xl font-bold text-white">{{ $dest->name }}</h3>
                                <a href="#" class="text-brand-accent text-sm hover:text-white transition">View Details &rarr;</a>
                            </div>

                            <div class="absolute bottom-6 left-6 group-hover:opacity-0 transition duration-300">
                                <h3 class="text-2xl font-bold text-white tracking-tighter">{{ $dest->name }}</h3>
                            </div>
                        </div>
                    @endforeach
                </div>

            </div>
        @else
            <div class="text-center text-slate-500 py-10">
                Not enough featured destinations to display grid (Need 3).
            </div>
        @endif
    </div>
</section>
