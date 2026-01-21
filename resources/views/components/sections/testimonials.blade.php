<section class="py-16 md:py-24 bg-[#f9f9f9] relative font-sans overflow-hidden" x-data="{
    init() {
        let checkSwiper = setInterval(() => {
            if (typeof Swiper !== 'undefined') {
                clearInterval(checkSwiper);
                new Swiper(this.$refs.testimonialSwiper, {
                    modules: [SwiperModules.Navigation, SwiperModules.Pagination],
                    slidesPerView: 1,
                    spaceBetween: 30,
                    loop: true,
                    grabCursor: true,
                    breakpoints: {
                        640: { slidesPerView: 1, spaceBetween: 20 },
                        768: { slidesPerView: 2, spaceBetween: 30 },
                        1024: { slidesPerView: 3, spaceBetween: 40 },
                    },
                    pagination: {
                        el: '.testimonial-pagination',
                        clickable: true,
                    },
                    navigation: {
                        nextEl: '.testimonial-next',
                        prevEl: '.testimonial-prev',
                    },
                });
            }
        }, 100);
    }
}">
    {{-- Decorative Background --}}
    <div class="absolute top-0 left-0 w-full h-full overflow-hidden pointer-events-none">
        <div class="absolute top-10 right-10 w-20 h-20 bg-brand-primary/5 rounded-full blur-2xl"></div>
        <div class="absolute bottom-10 left-10 w-32 h-32 bg-brand-accent/5 rounded-full blur-2xl"></div>
    </div>

    <div class="container mx-auto px-6 md:px-12 lg:px-20 relative z-10">
        
        {{-- Section Header --}}
        <div class="text-center mb-12 md:mb-16">
            <span class="inline-block bg-orange-100 text-orange-500 font-hand text-2xl md:text-3xl px-4 py-2 rounded-full mb-3 transform -rotate-2">
                Testimonials
            </span>
            <h2 class="text-4xl md:text-6xl font-extrabold text-brand-dark tracking-tight leading-tight">
                What Travelers <span class="text-brand-primary">Say About Us</span>
            </h2>
            <p class="text-slate-500 text-lg mt-4 max-w-2xl mx-auto">
                Real stories from travelers who have explored the beauty of East Java with BromoIjen Expedition.
            </p>
        </div>

        {{-- Slider Container --}}
        <div class="relative">
            
            {{-- Navigation Buttons --}}
            <button class="testimonial-prev absolute top-1/2 -left-4 md:-left-12 z-10 w-12 h-12 rounded-full bg-white shadow-lg text-brand-dark hover:bg-brand-primary hover:text-white transition-all flex items-center justify-center -translate-y-1/2 group">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5 group-hover:-translate-x-0.5 transition-transform">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                </svg>
            </button>
            <button class="testimonial-next absolute top-1/2 -right-4 md:-right-12 z-10 w-12 h-12 rounded-full bg-white shadow-lg text-brand-dark hover:bg-brand-primary hover:text-white transition-all flex items-center justify-center -translate-y-1/2 group">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5 group-hover:translate-x-0.5 transition-transform">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                </svg>
            </button>

            <div class="swiper" x-ref="testimonialSwiper">
                <div class="swiper-wrapper py-4 pb-12">
                    
                    {{-- Testimonial 1 --}}
                    <div class="swiper-slide h-auto">
                        <div class="bg-white p-5 sm:p-8 rounded-[24px] sm:rounded-[40px] shadow-lg border border-slate-50 h-full flex flex-col relative">
                            {{-- Google Icon (Decoration) --}}
                            <div class="absolute top-5 right-5 sm:top-8 sm:right-8 text-brand-primary/10">
                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="currentColor"><path d="M12 0c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-12-12-12-12zm-2 16h-2v-6h2v6zm-1-6.891c-.607 0-1.1-.496-1.1-1.109 0-.612.492-1.109 1.1-1.109s1.1.497 1.1 1.109c0 .613-.493 1.109-1.1 1.109zm8 6.891h-1.998v-2.861c0-1.881-2.002-1.722-2.002 0v2.861h-2v-6h2v1.093c.872-1.616 4-1.736 4 1.548v3.359z"/></svg> 
                            </div>

                            {{-- Stars --}}
                            <div class="flex text-yellow-400 mb-4 sm:mb-6">
                                @for($i=0; $i<5; $i++)
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                                        <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z" clip-rule="evenodd" />
                                    </svg>
                                @endfor
                            </div>

                            <p class="text-slate-600 text-base sm:text-lg italic mb-6 sm:mb-8 leading-relaxed flex-1">
                                "The Midnight Bromo Tour was absolutely breathtaking! The jeep ride, the sunrise, everything was perfectly organized. Our guide was very knowledgeable and friendly."
                            </p>

                            <div class="flex items-center gap-3 sm:gap-4 mt-auto">
                                <img src="https://ui-avatars.com/api/?name=Sarah+Jenner&background=random" class="w-14 h-14 rounded-full object-cover">
                                <div>
                                    <h4 class="font-bold text-brand-dark text-lg">Sarah Jenner</h4>
                                    <p class="text-xs text-slate-400 font-bold uppercase tracking-wider">Australia</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Testimonial 2 --}}
                    <div class="swiper-slide h-auto">
                        <div class="bg-white p-5 sm:p-8 rounded-[24px] sm:rounded-[40px] shadow-lg border border-slate-50 h-full flex flex-col relative">
                             <div class="absolute top-5 right-5 sm:top-8 sm:right-8 text-brand-primary/10">
                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="currentColor"><path d="M12 0c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-12-12-12-12zm-2 16h-2v-6h2v6zm-1-6.891c-.607 0-1.1-.496-1.1-1.109 0-.612.492-1.109 1.1-1.109s1.1.497 1.1 1.109c0 .613-.493 1.109-1.1 1.109zm8 6.891h-1.998v-2.861c0-1.881-2.002-1.722-2.002 0v2.861h-2v-6h2v1.093c.872-1.616 4-1.736 4 1.548v3.359z"/></svg> 
                            </div>
                            <div class="flex text-yellow-400 mb-4 sm:mb-6">
                                @for($i=0; $i<5; $i++)
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                                        <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z" clip-rule="evenodd" />
                                    </svg>
                                @endfor
                            </div>

                            <p class="text-slate-600 text-base sm:text-lg italic mb-6 sm:mb-8 leading-relaxed flex-1">
                                "Ijen Blue Fire was a challenging hike but totally worth it! BromoIjen team made sure we were safe and provided masks. Highly recommended service."
                            </p>

                            <div class="flex items-center gap-3 sm:gap-4 mt-auto">
                                <img src="https://ui-avatars.com/api/?name=Michael+Chen&background=random" class="w-14 h-14 rounded-full object-cover">
                                <div>
                                    <h4 class="font-bold text-brand-dark text-lg">Michael Chen</h4>
                                    <p class="text-xs text-slate-400 font-bold uppercase tracking-wider">Singapore</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Testimonial 3 --}}
                    <div class="swiper-slide h-auto">
                        <div class="bg-white p-5 sm:p-8 rounded-[24px] sm:rounded-[40px] shadow-lg border border-slate-50 h-full flex flex-col relative">
                             <div class="absolute top-5 right-5 sm:top-8 sm:right-8 text-brand-primary/10">
                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="currentColor"><path d="M12 0c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-12-12-12-12zm-2 16h-2v-6h2v6zm-1-6.891c-.607 0-1.1-.496-1.1-1.109 0-.612.492-1.109 1.1-1.109s1.1.497 1.1 1.109c0 .613-.493 1.109-1.1 1.109zm8 6.891h-1.998v-2.861c0-1.881-2.002-1.722-2.002 0v2.861h-2v-6h2v1.093c.872-1.616 4-1.736 4 1.548v3.359z"/></svg> 
                            </div>
                            <div class="flex text-yellow-400 mb-4 sm:mb-6">
                                @for($i=0; $i<5; $i++)
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                                        <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z" clip-rule="evenodd" />
                                    </svg>
                                @endfor
                            </div>

                            <p class="text-slate-600 text-base sm:text-lg italic mb-6 sm:mb-8 leading-relaxed flex-1">
                                "Booking was easy and the customer service was responsive via WhatsApp. The driver pick us up on time. A hassle-free experience!"
                            </p>

                            <div class="flex items-center gap-3 sm:gap-4 mt-auto">
                                <img src="https://ui-avatars.com/api/?name=Emma+Watson&background=random" class="w-14 h-14 rounded-full object-cover">
                                <div>
                                    <h4 class="font-bold text-brand-dark text-lg">Emma Watson</h4>
                                    <p class="text-xs text-slate-400 font-bold uppercase tracking-wider">United Kingdom</p>
                                </div>
                            </div>
                        </div>
                    </div>
                     {{-- Testimonial 4 --}}
                    <div class="swiper-slide h-auto">
                        <div class="bg-white p-5 sm:p-8 rounded-[24px] sm:rounded-[40px] shadow-lg border border-slate-50 h-full flex flex-col relative">
                             <div class="absolute top-5 right-5 sm:top-8 sm:right-8 text-brand-primary/10">
                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="currentColor"><path d="M12 0c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-12-12-12-12zm-2 16h-2v-6h2v6zm-1-6.891c-.607 0-1.1-.496-1.1-1.109 0-.612.492-1.109 1.1-1.109s1.1.497 1.1 1.109c0 .613-.493 1.109-1.1 1.109zm8 6.891h-1.998v-2.861c0-1.881-2.002-1.722-2.002 0v2.861h-2v-6h2v1.093c.872-1.616 4-1.736 4 1.548v3.359z"/></svg> 
                            </div>
                            <div class="flex text-yellow-400 mb-4 sm:mb-6">
                                @for($i=0; $i<5; $i++)
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                                        <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z" clip-rule="evenodd" />
                                    </svg>
                                @endfor
                            </div>

                            <p class="text-slate-600 text-base sm:text-lg italic mb-6 sm:mb-8 leading-relaxed flex-1">
                                "The scenery at Mount Bromo is unlike anything I've ever seen. BromoIjen Expedition handled everything perfectly."
                            </p>

                            <div class="flex items-center gap-3 sm:gap-4 mt-auto">
                                <img src="https://ui-avatars.com/api/?name=David+Kim&background=random" class="w-14 h-14 rounded-full object-cover">
                                <div>
                                    <h4 class="font-bold text-brand-dark text-lg">David Kim</h4>
                                    <p class="text-xs text-slate-400 font-bold uppercase tracking-wider">South Korea</p>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
                
                <div class="testimonial-pagination !bottom-0 mt-8"></div>
            </div>
        </div>
    </div>
</section>
