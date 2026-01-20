@props(['testimonials' => [
    [
        'name' => 'Sarah Jenner',
        'role' => 'Melbourne, Australia',
        'avatar' => 'https://ui-avatars.com/api/?name=Sarah+Jenner&background=random',
        'content' => 'The Midnight Bromo Tour was absolutely breathtaking! The jeep ride under the stars and the sunrise view were magical. Perfectly organized!',
        'rating' => 5
    ],
    [
        'name' => 'Michael Chen',
        'role' => 'Singapore',
        'avatar' => 'https://ui-avatars.com/api/?name=Michael+Chen&background=random',
        'content' => 'Ijen Blue Fire was a challenging hike but totally worth it. The guide ensured our safety throughout the trek. A premium experience.',
        'rating' => 5
    ],
    [
        'name' => 'Emma Watson',
        'role' => 'London, UK',
        'avatar' => 'https://ui-avatars.com/api/?name=Emma+Watson&background=random',
        'content' => 'Booking was seamless via WhatsApp. The driver was punctual and the car was very comfortable. Highly recommended for solo travelers.',
        'rating' => 5
    ],
    [
        'name' => 'David Kim',
        'role' => 'Seoul, South Korea',
        'avatar' => 'https://ui-avatars.com/api/?name=David+Kim&background=random',
        'content' => 'The scenery at massive Bromo crater is unlike anything I\'ve ever seen. BromoIjen Expedition handled everything professionally.',
        'rating' => 5
    ],
    [
        'name' => 'Jessica Brown',
        'role' => 'Toronto, Canada',
        'avatar' => 'https://ui-avatars.com/api/?name=Jessica+Brown&background=random',
        'content' => 'An unforgettable adventure! The Tumpak Sewu waterfall trip was the highlight of our Java journey. Great local guides.',
        'rating' => 5
    ],
]])

<section class="py-16 md:py-24 bg-[#f9f9f9] relative font-sans overflow-hidden">
    
    {{-- CSS for Marquee Animation --}}
    <style>
        @keyframes marquee {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }
        .animate-marquee {
            animation: marquee 40s linear infinite;
        }
        .group:hover .animate-marquee {
            animation-play-state: paused;
        }
    </style>

    {{-- Decorative Background --}}
    <div class="absolute top-0 left-0 w-full h-full overflow-hidden pointer-events-none">
        <div class="absolute top-10 right-10 w-32 h-32 bg-brand-primary/5 rounded-full blur-3xl"></div>
        <div class="absolute bottom-10 left-10 w-48 h-48 bg-brand-accent/5 rounded-full blur-3xl"></div>
    </div>

    <div class="container mx-auto px-6 mb-12 relative z-10 text-center">
        <span class="inline-block bg-orange-100 text-orange-500 font-hand text-2xl md:text-3xl px-4 py-2 rounded-full mb-3 transform -rotate-2">
            Community Love
        </span>
        <h2 class="text-4xl md:text-6xl font-extrabold text-brand-dark tracking-tight leading-tight">
            Trusted by <span class="text-brand-primary">Adventurers</span>
        </h2>
    </div>

    {{-- Marquee Wrapper --}}
    <div class="relative w-full overflow-hidden group">
        
        {{-- Gradient Masks --}}
        <div class="absolute inset-y-0 left-0 w-24 md:w-48 bg-gradient-to-r from-[#f9f9f9] to-transparent z-20 pointer-events-none"></div>
        <div class="absolute inset-y-0 right-0 w-24 md:w-48 bg-gradient-to-l from-[#f9f9f9] to-transparent z-20 pointer-events-none"></div>

        {{-- Moving Container --}}
        <div class="flex items-stretch gap-6 animate-marquee w-max py-4 pl-6">
            
            {{-- Loop Twice for seamless infinite scroll --}}
            @for ($i = 0; $i < 2; $i++)
                @foreach ($testimonials as $testimonial)
                    <div class="w-[320px] md:w-[400px] bg-white border border-slate-100 p-8 rounded-[30px] shadow-[0_4px_20px_rgba(0,0,0,0.03)] hover:shadow-xl transition-all duration-300 flex-shrink-0 flex flex-col relative group/card">
                        
                        {{-- Quote Icon --}}
                        <div class="absolute top-8 right-8 text-brand-primary/10 group-hover/card:text-brand-primary/20 transition-colors">
                             <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="currentColor"><path d="M14.017 21L14.017 18C14.017 16.8954 14.9124 16 16.017 16H19.017C19.5693 16 20.017 15.5523 20.017 15V9C20.017 8.44772 19.5693 8 19.017 8H15.017C14.4647 8 14.017 8.44772 14.017 9V11C14.017 11.5523 13.5693 12 13.017 12H12.017V5H22.017V15C22.017 18.3137 19.3307 21 16.017 21H14.017ZM5.01691 21L5.01691 18C5.01691 16.8954 5.91234 16 7.01691 16H10.0169C10.5692 16 11.0169 15.5523 11.0169 15V9C11.0169 8.44772 10.5692 8 10.0169 8H6.01691C5.46462 8 5.01691 8.44772 5.01691 9V11C5.01691 11.5523 4.56919 12 4.01691 12H3.01691V5H13.0169V15C13.0169 18.3137 10.3306 21 7.01691 21H5.01691Z" /></svg>
                        </div>

                        {{-- Stars --}}
                        <div class="flex text-yellow-400 mb-6 relative z-10">
                            @for($s=0; $s<5; $s++)
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                                    <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z" clip-rule="evenodd" />
                                </svg>
                            @endfor
                        </div>

                        {{-- Review Content --}}
                        <p class="text-slate-600 text-lg italic mb-6 leading-relaxed flex-1 z-10 relative">
                            "{{ $testimonial['content'] }}"
                        </p>
                        
                        {{-- Footer: Avatar --}}
                        <div class="flex items-center gap-4 mt-auto relative z-10 border-t border-slate-50 pt-6">
                            <img src="{{ $testimonial['avatar'] ?? 'https://ui-avatars.com/api/?name='.urlencode($testimonial['name']).'&background=random' }}" alt="{{ $testimonial['name'] }}" class="w-12 h-12 rounded-full border border-slate-100">
                            <div>
                                <h4 class="text-brand-dark font-bold text-base">{{ $testimonial['name'] }}</h4>
                                <p class="text-slate-400 text-xs font-bold uppercase tracking-wider">{{ $testimonial['role'] }}</p>
                            </div>
                        </div>

                    </div>
                @endforeach
            @endfor

        </div>
    </div>
</section>
