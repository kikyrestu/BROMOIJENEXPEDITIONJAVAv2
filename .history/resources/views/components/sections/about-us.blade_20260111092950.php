<section class="py-12 md:py-16 bg-[#f9f9f9] relative font-sans overflow-hidden">
    
    {{-- Decorative Background Elements (Maps/Planes) --}}
    <div class="absolute top-20 left-10 opacity-10 pointer-events-none">
        <svg width="80" height="80" viewBox="0 0 100 100" fill="none" class="text-brand-accent animate-pulse">
            <path d="M10 50 Q 50 10 90 50" stroke="currentColor" stroke-width="2" stroke-dasharray="4 4"/>
        </svg>
    </div>

    <div class="container mx-auto px-6 md:px-12 lg:px-20 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12 items-center">
            
            {{-- LEFT: Image Collage --}}
            <div class="relative w-full max-w-sm mx-auto lg:max-w-none">
                
                {{-- Main Image (Rounded Top Left) --}}
                <div class="relative z-10 w-[65%] ml-auto lg:ml-8 aspect-[4/5] rounded-tl-[60px] rounded-br-[30px] overflow-hidden shadow-xl border-4 border-white">
                    <img src="https://placehold.co/600x800?text=Adventure" class="w-full h-full object-cover">
                    
                    {{-- Play Button Overlay --}}
                    <div class="absolute inset-0 flex items-center justify-center">
                        <button class="w-12 h-12 bg-white text-brand-accent rounded-full flex items-center justify-center shadow-lg hover:scale-110 transition-transform cursor-pointer">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 ml-0.5">
                                <path fill-rule="evenodd" d="M4.5 5.653c0-1.426 1.529-2.33 2.779-1.643l11.54 6.348c1.295.712 1.295 2.573 0 3.285L7.28 19.991c-1.25.687-2.779-.217-2.779-1.643V5.653z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Secondary Image (Floating Bottom Right) --}}
                <div class="absolute -bottom-6 -right-2 md:right-8 lg:right-16 w-[45%] aspect-square bg-white p-2 rounded-tl-[30px] rounded-br-[30px] shadow-lg z-20 hidden md:block border border-slate-100">
                     <img src="https://placehold.co/500x500?text=Joy" class="w-full h-full object-cover rounded-tl-[24px] rounded-br-[24px]">
                </div>

                {{-- Decorative Green Bar --}}
                <div class="absolute top-6 right-0 lg:right-8 w-2 h-20 bg-brand-primary rounded-full hidden lg:block"></div>
            </div>

            {{-- RIGHT: Text Content --}}
            <div class="lg:pl-6 mt-8 lg:mt-0 text-center lg:text-left">
                <span class="inline-block bg-orange-100 text-orange-500 font-hand text-2xl md:text-3xl px-4 py-2 rounded-full mb-3 transform -rotate-2">
                    About BromoIjen
                </span>
                
                <h2 class="text-4xl md:text-6xl font-extrabold text-brand-dark leading-tight mb-6 tracking-tight">
                    Experience The <span class="text-brand-primary">New Adventure</span> With Us
                </h2>
                
                <p class="text-slate-500 text-lg mb-8 leading-relaxed max-w-2xl mx-auto lg:mx-0">
                    We organize premium trips to Mount Bromo, Ijen Crater, and other exotic destinations in East Java. Our goal is to provide safe, comfortable, and memorable experiences for every traveler.
                </p>

                {{-- Features Grid --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8 text-left">
                    {{-- Feature 1 --}}
                    <div class="flex items-start gap-4 justify-center lg:justify-start">
                        <div class="w-12 h-12 bg-green-50 text-brand-primary rounded-xl flex items-center justify-center shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 0 1-1.043 3.296 3.745 3.745 0 0 1-3.296 1.043A3.745 3.745 0 0 1 12 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 0 1-3.296-1.043 3.745 3.745 0 0 1-1.043-3.296A3.745 3.745 0 0 1 3 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 0 1 1.043-3.296 3.746 3.746 0 0 1 3.296-1.043A3.746 3.746 0 0 1 12 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 0 1 3.296 1.043 3.746 3.746 0 0 1 1.043 3.296A3.745 3.745 0 0 1 21 12Z" />
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-brand-dark text-xl mb-1">Trusted Travel Guide</h4>
                            <p class="text-slate-400 text-sm">Professional English speaking guides.</p>
                        </div>
                    </div>
                    
                    {{-- Feature 2 --}}
                    <div class="flex items-start gap-4 justify-center lg:justify-start">
                        <div class="w-12 h-12 bg-green-50 text-brand-primary rounded-xl flex items-center justify-center shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0h18M5.25 6h13.5A2.25 2.25 0 0 1 21 7.5v11.25a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 18.75V7.5A2.25 2.25 0 0 1 5.25 6Z" />
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-brand-dark text-xl mb-1">Instant Booking</h4>
                            <p class="text-slate-400 text-sm">Easy and secure online booking.</p>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-6 border-t border-slate-200 pt-6">
                    {{-- CTA Button --}}
                    <a href="{{ route('packages.index') }}" class="px-6 py-3 bg-brand-accent hover:bg-brand-primary text-white font-bold rounded-full shadow-md hover:shadow-lg transition-all flex items-center gap-2 text-sm group">
                        Discover More
                        <div class="w-5 h-5 bg-white text-brand-accent rounded-full flex items-center justify-center group-hover:text-brand-primary">
                             <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-2.5 h-2.5">
                                <path fill-rule="evenodd" d="M3 10a.75.75 0 0 1 .75-.75h10.638L10.23 5.29a.75.75 0 1 1 1.04-1.08l5.5 5.25a.75.75 0 0 1 0 1.08l-5.5 5.25a.75.75 0 1 1-1.04-1.08l4.158-3.96H3.75A.75.75 0 0 1 3 10Z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </a>

                    {{-- Founder Profile --}}
                    <div class="flex items-center gap-3">
                        <img src="https://ui-avatars.com/api/?name=Admin+Bromo&background=random" class="w-10 h-10 rounded-full border-2 border-white shadow-sm">
                        <div class="text-left">
                            <h5 class="font-bold text-brand-dark text-sm">Agus Setiawan</h5>
                            <p class="text-[10px] text-slate-400">Founder, BromoIjen</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>
