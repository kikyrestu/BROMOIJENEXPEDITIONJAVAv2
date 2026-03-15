@props(['data'])

@php
    $selectedIds = $data['destinations'] ?? [];
    if (!empty($selectedIds)) {
        $destinations = \App\Models\Destination::whereIn('id', $selectedIds)->orderBy('sort_order')->get();
    } else {
        // Fallback: Show featured destinations if none selected in block
        $destinations = \App\Models\Destination::where('is_featured', true)->orderBy('sort_order')->limit(4)->get();
    }
@endphp

<section class="py-12 md:py-16 bg-white relative overflow-hidden" data-block-index="{{ $attributes->get('data-block-index') }}">
    
    {{-- Decorative Background Float --}}
    <div class="absolute top-0 right-0 w-64 h-64 bg-brand-primary/10 rounded-full blur-3xl -mr-32 -mt-32 pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 w-96 h-96 bg-brand-accent/5 rounded-full blur-3xl -ml-40 -mb-40 pointer-events-none"></div>

    <div class="container mx-auto px-6 md:px-12 lg:px-20 mb-16 text-center relative z-10">
        <span class="font-hand text-brand-accent text-3xl md:text-4xl block mb-2 transform -rotate-2" data-field="badge_text">
            {!! $data['badge_text'] ?? 'Choose your next adventure' !!}
        </span>
        <h2 class="text-4xl md:text-6xl font-extrabold text-brand-dark tracking-tight">
             <span data-field="title_prefix">{!! $data['title_prefix'] ?? 'Exclusive' !!}</span>
             <span class="relative inline-block text-brand-primary">
                <span data-field="title_suffix">{!! $data['title_suffix'] ?? 'Destinations' !!}</span>
                <svg class="absolute w-full h-3 -bottom-1 left-0 text-brand-accent opacity-40" viewBox="0 0 200 9" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M2.00025 6.99997C25.7501 2.875 163.75 -3.125 198 2.00015" stroke="currentColor" stroke-width="3"></path></svg>
            </span>
        </h2>
    </div>

    @if($destinations->count() >= 1)
        {{-- Grid Layout: always 1 row --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6 max-w-[1400px] mx-auto px-4">
            
            @foreach($destinations as $destination)
                <a href="#" class="group relative block transition-transform hover:-translate-y-2 duration-500">
                    
                    {{-- Destination Card --}}
                    <div class="group relative block w-full aspect-[3/5] mx-auto overflow-hidden bg-white shadow-lg 
                                rounded-tl-[120px] rounded-bl-[50px] rounded-br-[50px] rounded-tr-none 
                                hover:rounded-tr-[50px] transition-all duration-500 ease-in-out">
                        
                        {{-- Image --}}
                        <div class="h-full w-full">
                            @php
                                $dThumb = $destination->thumbnail_path;
                                $dMedium = (!empty($dThumb) && !str_starts_with($dThumb, 'http')) ? \App\Services\ImageOptimizationService::getMediumUrl($dThumb) : null;
                                $dSrc = $dThumb ? Storage::disk('public')->url($dThumb) : 'https://placehold.co/600x800?text='.urlencode($destination->name);
                            @endphp
                            <img src="{{ $dMedium ?? $dSrc }}" 
                                 @if($dMedium) srcset="{{ $dMedium }} 600w, {{ $dSrc }} 1080w" sizes="(max-width: 768px) 50vw, 33vw" @endif
                                 loading="lazy" 
                                 alt="{{ $destination->name }}" 
                                 class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-110">
                        </div>

                        {{-- Overlay Content --}}
                        <div class="absolute inset-x-0 bottom-0 p-6 bg-gradient-to-t from-black/80 via-black/40 to-transparent">
                            <h3 class="text-2xl font-bold text-white font-hand tracking-widest">{{ $destination->name }}</h3>
                            <p class="text-brand-accent text-sm font-bold uppercase tracking-wider">{{ $destination->location_city ?? 'East Java' }}</p>
                        </div>
                        
                        {{-- Hover Button --}}
                        <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none">
                            <span class="px-6 py-2 bg-brand-accent/90 text-white rounded-full font-bold backdrop-blur-sm transform translate-y-4 group-hover:translate-y-0 transition-transform duration-300">
                                Explore
                            </span>
                        </div>
                    </div>
                </a>
            @endforeach
            
        </div>
    @else
        <div class="text-center py-24 text-slate-400 bg-white rounded-3xl border border-slate-100 shadow-sm">
            <p class="font-medium text-lg" data-field="empty_text">{!! $data['empty_text'] ?? 'Destinations coming soon.' !!}</p>
        </div>
    @endif

</section>
