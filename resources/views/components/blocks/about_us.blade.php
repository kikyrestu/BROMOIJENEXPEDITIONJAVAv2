@props(['data'])

@php
    $mediaType = $data['media_type'] ?? 'image';
    $sourceType = $data['source_type'] ?? 'upload';
    

    $mediaUrl = '#'; // Default
    if ($sourceType === 'media_library' && !empty($data['media_id'])) {
        $media = \App\Models\Media::find($data['media_id']);
        $mediaUrl = $media ? $media->url : '#';
    } elseif ($mediaType === 'image') {
        $mediaUrl = ($sourceType === 'upload' && !empty($data['image_file'])) 
            ? Storage::disk('public')->url($data['image_file']) 
            : ($data['image_url'] ?? 'https://placehold.co/600x800?text=Adventure');
    } elseif ($mediaType === 'video') {
         $mediaUrl = ($sourceType === 'upload' && !empty($data['video_file'])) 
            ? Storage::disk('public')->url($data['video_file']) 
            : ($data['video_url'] ?? '#');
    }

    // Secondary Image Logic
    $secSource = $data['secondary_source_type'] ?? 'url';
    $secondaryImageUrl = 'https://placehold.co/500x500?text=Joy';
    if ($secSource === 'media_library' && !empty($data['secondary_media_id'])) {
        $secMedia = \App\Models\Media::find($data['secondary_media_id']);
        if ($secMedia) $secondaryImageUrl = $secMedia->url;
    } else {
        $secondaryImageUrl = $data['secondary_image_url'] ?? 'https://placehold.co/500x500?text=Joy';
    }

    $showCta = filter_var($data['show_cta'] ?? true, FILTER_VALIDATE_BOOLEAN);
    $showFounder = filter_var($data['show_founder'] ?? true, FILTER_VALIDATE_BOOLEAN);
    $ctaText = $data['cta_text'] ?? 'Discover More';
    $founderName = $data['founder_name'] ?? 'Agus Setiawan';
    $founderRole = $data['founder_role'] ?? 'Founder, BromoIjen';
@endphp

<section x-data="{
    inEditor: false,
    showCta: @json($showCta),
    showFounder: @json($showFounder),
    ctaText: '{{ addslashes($ctaText) }}',
    founderName: '{{ addslashes($founderName) }}',
    founderRole: '{{ addslashes($founderRole) }}',
    secondaryImage: '{{ $secondaryImageUrl }}',
    mainImage: '{{ $mediaUrl }}',

    init() {
        this.inEditor = window.self !== window.top;
        if (this.inEditor) {
            window.addEventListener('message', (e) => {
                if (e.data.type === 'UPDATE_BLOCK_FIELD' && e.data.index == this.$el.dataset.blockIndex) {
                    if (e.data.field === 'show_cta') this.showCta = e.data.value;
                    if (e.data.field === 'show_founder') this.showFounder = e.data.value;
                    if (e.data.field === 'cta_text') this.ctaText = e.data.value;
                    if (e.data.field === 'founder_name') this.founderName = e.data.value;
                    if (e.data.field === 'founder_role') this.founderRole = e.data.value;
                    
                    // Live Media Updates
                    if (e.data.field === 'secondary_image_url') this.secondaryImage = e.data.value;
                    if (e.data.field === 'image_url') this.mainImage = e.data.value;
                    if (e.data.field === 'video_url') this.mainImage = e.data.value;
                }
            });
        }
    }
}" 
class="py-12 md:py-16 bg-[#f9f9f9] relative font-sans overflow-hidden" 
data-block-index="{{ $attributes->get('data-block-index') }}">

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
                     @if($mediaType === 'video')
                         <video autoplay loop muted playsinline class="w-full h-full object-cover" :src="mainImage">
                            <source src="{{ $mediaUrl }}" type="video/mp4">
                        </video>
                    @else
                        <img :src="mainImage" src="{{ $mediaUrl }}" class="w-full h-full object-cover">
                    @endif
                    
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
                     <img :src="secondaryImage" src="{{ $secondaryImageUrl }}" class="w-full h-full object-cover rounded-tl-[24px] rounded-br-[24px]">
                </div>

                {{-- Decorative Green Bar --}}
                <div class="absolute top-6 right-0 lg:right-8 w-2 h-20 bg-brand-primary rounded-full hidden lg:block"></div>
            </div>

            {{-- RIGHT: Text Content --}}
            <div class="lg:pl-6 mt-8 lg:mt-0 text-center lg:text-left">
                <span class="inline-block bg-orange-100 text-orange-500 font-hand text-2xl md:text-3xl px-4 py-2 rounded-full mb-3 transform -rotate-2" data-field="badge_text">
                    {!! $data['badge_text'] ?? 'About BromoIjen' !!}
                </span>
                
                {{-- Keeping original fields static/Blade-only for now unless user demands full live edit for these --}}
                <h2 class="text-4xl md:text-6xl font-extrabold text-brand-dark leading-tight mb-6 tracking-tight" data-field="title">
                    {!! $data['title'] !!}
                </h2>
                
                <div class="text-slate-500 text-lg mb-8 leading-relaxed max-w-2xl mx-auto lg:mx-0" data-field="content">
                    {!! $data['content'] !!}
                </div>

                {{-- Features Grid (Static) --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8 text-left">
                    {{-- Feature 1 --}}
                    <div class="flex items-start gap-4 justify-center lg:justify-start">
                        <div class="w-12 h-12 bg-green-50 text-brand-primary rounded-xl flex items-center justify-center shrink-0">
                            <!-- Icon can be dynamic too later, for now hardcoded SVG -->
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 0 1-1.043 3.296 3.745 3.745 0 0 1-3.296 1.043A3.745 3.745 0 0 1 12 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 0 1-3.296-1.043 3.745 3.745 0 0 1-1.043-3.296A3.745 3.745 0 0 1 3 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 0 1 1.043-3.296 3.746 3.746 0 0 1 3.296-1.043A3.746 3.746 0 0 1 12 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 0 1 3.296 1.043 3.746 3.746 0 0 1 1.043 3.296A3.745 3.745 0 0 1 21 12Z" />
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-brand-dark text-xl mb-1" data-field="feature_1_title">{!! $data['feature_1_title'] ?? 'Trusted Travel Guide' !!}</h4>
                            <p class="text-slate-400 text-sm" data-field="feature_1_text">{!! $data['feature_1_text'] ?? 'Professional English speaking guides.' !!}</p>
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
                            <h4 class="font-bold text-brand-dark text-xl mb-1" data-field="feature_2_title">{!! $data['feature_2_title'] ?? 'Instant Booking' !!}</h4>
                            <p class="text-slate-400 text-sm" data-field="feature_2_text">{!! $data['feature_2_text'] ?? 'Easy and secure online booking.' !!}</p>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-6 border-t border-slate-200 pt-6 transition-all duration-300">
                    {{-- CTA Button --}}
                    <a href="{{ route('packages.index') }}" 
                       x-show="showCta"
                       x-transition:enter="transition ease-out duration-300"
                       x-transition:enter-start="opacity-0 scale-90"
                       x-transition:enter-end="opacity-100 scale-100"
                       x-transition:leave="transition ease-in duration-300 absolute"
                       x-transition:leave-start="opacity-100 scale-100"
                       x-transition:leave-end="opacity-0 scale-90"
                       class="px-6 py-3 bg-brand-accent hover:bg-brand-primary text-white font-bold rounded-full shadow-md hover:shadow-lg transition-all flex items-center gap-2 text-sm group">
                        <span x-text="ctaText" data-field="cta_text">{!! $data['cta_text'] ?? 'Discover More' !!}</span>
                        <div class="w-5 h-5 bg-white text-brand-accent rounded-full flex items-center justify-center group-hover:text-brand-primary">
                             <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-2.5 h-2.5">
                                <path fill-rule="evenodd" d="M3 10a.75.75 0 0 1 .75-.75h10.638L10.23 5.29a.75.75 0 1 1 1.04-1.08l5.5 5.25a.75.75 0 0 1 0 1.08l-5.5 5.25a.75.75 0 1 1-1.04-1.08l4.158-3.96H3.75A.75.75 0 0 1 3 10Z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </a>

                    {{-- Founder Profile --}}
                    <div x-show="showFounder" 
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 translate-x-4"
                         x-transition:enter-end="opacity-100 translate-x-0"
                         x-transition:leave="transition ease-in duration-300 absolute"
                         x-transition:leave-start="opacity-100 translate-x-0"
                         x-transition:leave-end="opacity-0 translate-x-4"
                         class="flex items-center gap-3">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($data['founder_name'] ?? 'Admin Bromo') }}&background=random" class="w-10 h-10 rounded-full border-2 border-white shadow-sm">
                        <div class="text-left">
                            <h5 class="font-bold text-brand-dark text-sm" x-text="founderName" data-field="founder_name">{!! $data['founder_name'] ?? 'Agus Setiawan' !!}</h5>
                            <p class="text-[10px] text-slate-400" x-text="founderRole" data-field="founder_role">{!! $data['founder_role'] ?? 'Founder, BromoIjen' !!}</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>
