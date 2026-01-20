@props(['data'])

<section class="py-16 md:py-24 bg-white relative font-sans overflow-hidden" data-block-index="{{ $attributes->get('data-block-index') }}">
    
    <div class="container mx-auto px-6 md:px-12 lg:px-20 relative z-10">
        
        {{-- Section Header --}}
        <div class="text-center max-w-3xl mx-auto mb-12">
            <span class="inline-block bg-orange-100 text-orange-500 font-hand text-xl px-4 py-1 rounded-full mb-3 transform -rotate-1" data-field="badge_text">
                {!! $data['badge_text'] ?? 'Our Memories' !!}
            </span>
            <h2 class="text-4xl md:text-5xl font-extrabold text-brand-dark tracking-tight leading-tight mb-4">
                 <span data-field="title_prefix">{!! $data['title_prefix'] ?? 'Capture The' !!}</span> 
                 <span class="text-brand-primary font-hand italic" data-field="title_suffix">{!! $data['title_suffix'] ?? 'Moments' !!}</span>
            </h2>
            <p class="text-slate-500 text-lg" data-field="description">
                {!! $data['description'] ?? 'Explore the beauty of East Java through our lens. From the sunrise of Bromo to the blue fire of Ijen.' !!}
            </p>
        </div>

        {{-- Gallery Grid --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 auto-rows-[200px]">
            {{-- Static Mock Images for now, can be made dynamic later --}}
            
            {{-- Item 1 (Large Square) --}}
            <div class="col-span-2 row-span-2 group relative rounded-3xl overflow-hidden cursor-pointer">
                <img src="https://placehold.co/800x800?text=Bromo+Sunrise" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                <div class="absolute inset-0 bg-black/20 group-hover:bg-black/40 transition-colors"></div>
                <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    <div class="w-12 h-12 bg-white/20 backdrop-blur-md rounded-full flex items-center justify-center text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Item 2 --}}
            <div class="col-span-1 row-span-1 group relative rounded-3xl overflow-hidden cursor-pointer">
                <img src="https://placehold.co/400x400?text=Jeep+Ride" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-colors"></div>
            </div>

            {{-- Item 3 --}}
            <div class="col-span-1 row-span-1 group relative rounded-3xl overflow-hidden cursor-pointer">
                <img src="https://placehold.co/400x400?text=Ijen+Crater" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-colors"></div>
            </div>

            {{-- Item 4 (Tall) --}}
            <div class="col-span-1 row-span-2 group relative rounded-3xl overflow-hidden cursor-pointer">
                <img src="https://placehold.co/400x800?text=Savana" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-colors"></div>
            </div>

            {{-- Item 5 --}}
            <div class="col-span-1 row-span-1 group relative rounded-3xl overflow-hidden cursor-pointer">
                <img src="https://placehold.co/400x400?text=People" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-colors"></div>
            </div>

            {{-- Item 6 (Wide) --}}
            <div class="col-span-2 row-span-1 group relative rounded-3xl overflow-hidden cursor-pointer">
                <img src="https://placehold.co/800x400?text=Milky+Way" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                 <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-colors"></div>
            </div>

        </div>

         {{-- View More Text --}}
         <div class="text-center mt-10">
            <a href="#" class="inline-flex flex-col items-center gap-2 text-slate-400 font-bold hover:text-brand-primary transition-colors group">
                <span class="tracking-widest uppercase text-xs" data-field="view_more_text">{!! $data['view_more_text'] ?? 'View More' !!}</span>
                <div class="w-8 h-8 rounded-full border border-slate-200 flex items-center justify-center group-hover:border-brand-primary group-hover:bg-brand-primary group-hover:text-white transition-all animate-bounce">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                    </svg>
                </div>
            </a>
         </div>

    </div>
</section>
