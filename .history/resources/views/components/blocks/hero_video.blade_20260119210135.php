@props(['data'])

@php
    $template = $data['template'] ?? 'default';
    
    // Background Slides Logic
    $slides = [];
    $rawBackgrounds = $data['backgrounds'] ?? [];

    if (empty($rawBackgrounds) || !is_array($rawBackgrounds)) {
        // Fallback Logic
        $legacyUrl = null;
        $legacyType = 'video';
        if (($data['video_source'] ?? 'url') === 'media_library' && !empty($data['media_id'])) {
            $m = \App\Models\Media::find($data['media_id']);
            if ($m) { $legacyUrl = $m->url; $legacyType = ($m->type === 'video' || str_contains((string)$m->mime_type, 'video')) ? 'video' : 'image'; }
        } elseif (($data['video_source'] ?? 'url') === 'upload' && !empty($data['video_file'])) {
             $legacyUrl = \Illuminate\Support\Facades\Storage::disk('public')->url($data['video_file']); $legacyType = 'video'; 
        } else { $legacyUrl = $data['video_url'] ?? null; $legacyType = 'video'; }

        if ($legacyUrl && $legacyUrl !== '#') {
            $slides[] = ['type' => $legacyType, 'url' => $legacyUrl, 'mime_type' => $legacyType === 'video' ? 'video/mp4' : 'image/jpeg'];
        }
    } else {
        foreach($rawBackgrounds as $bg) {
            $slideUrl = null; $slideType = 'image';
            if (!empty($bg['id'])) {
                $m = \App\Models\Media::find($bg['id']);
                if ($m) { $slideUrl = $m->url; $slideType = ($m->type === 'video' || str_contains((string)$m->mime_type, 'video')) ? 'video' : 'image'; }
            } 
            if (empty($slideUrl) && !empty($bg['url'])) {
                $slideUrl = $bg['url'];
                if (!empty($bg['mime_type']) && str_contains($bg['mime_type'], 'video')) { $slideType = 'video'; } 
                elseif (str_ends_with($slideUrl, '.mp4') || str_ends_with($slideUrl, '.webm')) { $slideType = 'video'; }
            }
             if ($slideUrl) { 
                $slides[] = [
                    'type' => $slideType, 
                    'url' => $slideUrl, 
                    'mime_type' => $slideType === 'video' ? 'video/mp4' : 'image/jpeg',
                    'spots' => $bg['spots'] ?? []
                ]; 
            }
        }
    }
    if (empty($slides)) { $slides[] = ['type' => 'video', 'url' => '#', 'mime_type' => 'video/mp4']; }

    $heading = $data['heading'] ?? 'Explore Bromo & Ijen';
    $subheading = $data['subheading'] ?? 'The Ultimate Adventure in East Java';
    $buttonText = $data['button_text'] ?? 'Start Adventure';
    $buttonUrl = $data['button_url'] ?? '#packages';
    // FIX: Strict check. Handle boolean, string 'false', and 0. Default true only if key missing.
    $showButton = true;
    if (array_key_exists('show_button', $data)) {
        $val = $data['show_button'];
        if ($val === false || $val === 'false' || $val === 0 || $val === '0') {
            $showButton = false;
        }
    }
    $spots = []; // Legacy fallback deactivated
@endphp

<!-- ARCHITECTURE: GPU-Accelerated Sequence -->
<!-- ARCHITECTURE: Alpine.js State-Driven Anination (Double Trigger) -->
<style>
    [x-cloak] { display: none !important; }
</style>


<div x-data="{
    blockIndex: '{{ $attributes->get('data-block-index', uniqid()) }}',
    template: '{{ $template }}',
    slides: @js($slides),
    heading: @js($heading),
    subheading: @js($subheading),
    buttonText: @js($buttonText),
    buttonUrl: @js($buttonUrl),
    showButton: @json($showButton),
    activeSlide: 0,
    showSlide: false, 
    visible: false,
    inEditor: false,
    slideTimer: null,
    
    init() {
        this.inEditor = window.self !== window.top;
        this.showSlide = false;
        this.visible = false;
        
        // 1. Background Entrance
        setTimeout(() => {
            this.showSlide = true;
            this.runSlideLogic();
        }, 100);

        // 2. Text Sequence (Simple Transition)
        setTimeout(() => {
            this.visible = true;
            
            // 3. Exit Sequence
            setTimeout(() => {
                 this.visible = false;
            }, 6500); 
        }, 1500);

        if (this.inEditor) {
            window.addEventListener('message', (e) => {
                if (e.data.type === 'UPDATE_BLOCK_FIELD' && e.data.index == this.$el.dataset.blockIndex) {

                     
                     const texts = ['button_text', 'button_url', 'heading', 'subheading'];
                     if (texts.includes(e.data.field)) {
                         const prop = e.data.field.replace(/_([a-z])/g, (g) => g[1].toUpperCase());
                         if(this[prop] !== undefined) this[prop] = e.data.value;
                         if (e.data.field === 'heading') this.heading = e.data.value;
                         if (e.data.field === 'subheading') this.subheading = e.data.value;
                     }
                     if (e.data.field === 'show_button') {
                         this.showButton = e.data.value;

                     }
                     if (e.data.field === 'template') this.template = e.data.value;
                     
                     // Live Preview for Backgrounds & Spots
                     if (e.data.field === 'backgrounds') {
                        console.log('[Media Debug] Hero Backgrounds Updated:', e.data.value);
                         // Simple mapping to update slides structureClient-side
                         // Simple mapping to update slides structureClient-side
                         // Note: Media ID resolution isn't possible here without API, so we rely on 'url' being present in editor preview data
                         this.slides = e.data.value.map((bg, idx) => {

                             return {
                                 type: (bg.mime_type && bg.mime_type.includes('video')) || (bg.url && bg.url.endsWith('.mp4')) ? 'video' : 'image',
                                 url: bg.url || '#', // Fallback
                                 mime_type: 'video/mp4', // Simplified
                                 spots: bg.spots || []
                             };
                         });
                         
                         // Force Refresh
                         console.log('[Media Debug] Refreshing Slides...');
                         this.activeSlide = 0;
                         if (this.slideTimer) clearTimeout(this.slideTimer);
                         this.$nextTick(() => {
                             this.runSlideLogic();
                         });

                     }
                }
            });
        }
    },

    runSlideLogic() {
        if (this.slides.length <= 1) return;
        const currentSlide = this.slides[this.activeSlide];
        
        if (currentSlide.type === 'video') {
            
            
            // ID-Based Selection Strategy
            let attempts = 0;
            const maxAttempts = 10;
            const checkAndPlay = () => {
                attempts++;
                const videoId = `hero-video-${this.blockIndex}-${this.activeSlide}`;
                
                const vid = document.getElementById(videoId);
                
                if (vid) {
                    vid.muted = true;
                    vid.load();
                    setTimeout(() => {
                        vid.play().catch(e => console.log('Autoplay blocked:', e));
                    }, 100);
                } else if (attempts < maxAttempts) {
                    setTimeout(checkAndPlay, 50);
                }
            };
            
            checkAndPlay();
        } else {
            if (this.slideTimer) clearTimeout(this.slideTimer);
            this.slideTimer = setTimeout(() => { this.nextSlideLogic(); }, 5000);
        }
    },

    nextSlideLogic() {
        this.showSlide = false;
        setTimeout(() => {
            this.activeSlide = (this.activeSlide + 1) % this.slides.length;
            this.$nextTick(() => {
                this.showSlide = true;
                // Wait for Alpine's x-if to actually create the element
                setTimeout(() => {
                    this.runSlideLogic();
                }, 200);
            });
        }, 1200);
    },

    updateSpot(index, x, y) {
        if (!this.inEditor) return;
        if (this.slides[this.activeSlide] && this.slides[this.activeSlide].spots) {
             this.slides[this.activeSlide].spots[index].x = x;
             this.slides[this.activeSlide].spots[index].y = y;
             // Note: We might need to send activeSlide index too if the editor needs it, 
             // but usually ID or index is sufficient if the editor context is aware.
             // Assuming the editor knows we are editing the 'backgrounds' repeater item.
             window.parent.postMessage({ 
                 type: 'UPDATE_SPOT_COORDS', 
                 blockIndex: this.blockIndex, // Use the x-data prop
                 spotIndex: index, 
                 slideIndex: this.activeSlide, // Add slide context
                 x: x, 
                 y: y 
             }, '*');
        }
    }
}" 
class="relative w-full h-screen overflow-hidden text-white"
data-block-index="{{ $attributes->get('data-block-index') }}">

    <!-- BACKGROUND SLIDER -->
    <div class="absolute inset-0 z-0 bg-black">
        <template x-for="(slide, index) in slides" :key="index">
            <div class="absolute inset-0 transition-opacity duration-1000 ease-in-out"
                 :data-slide-index="index"
                 :class="{ 'opacity-100 z-10': activeSlide === index && showSlide, 'opacity-0 z-0': activeSlide !== index || !showSlide }">
                <!-- Video (Always Rendered, Toggled via x-show) -->
                <video x-show="slide.type === 'video'"
                       :id="'hero-video-' + blockIndex + '-' + index"
                       class="w-full h-full object-cover" 
                       autoplay muted playsinline 
                       :loop="slides.length === 1" 
                       @ended="nextSlideLogic()">
                    <source :src="slide.url" :type="slide.mime_type">
                </video>
                
                <!-- Image (Always Rendered, Toggled via x-show) -->
                <img x-show="slide.type !== 'video'" 
                     :src="slide.url" 
                     class="w-full h-full object-cover">
                     
                <div class="absolute inset-0 bg-black/30"></div>
                
                <!-- HOTSPOTS (Rendered per slide) -->
                <template x-if="template === 'hotspots'">
                    <div class="absolute inset-0 pointer-events-none">
                        <template x-for="(spot, spotIdx) in slide.spots || []" :key="spotIdx">
                            <div class="absolute cursor-pointer group pointer-events-auto" 
                                 :class="{'cursor-move': inEditor}" 
                                 :style="`left: ${spot.x}%; top: ${spot.y}%;`"
                                 @mousedown="inEditor ? null : false"> <!-- Prevent drag if not editor -->
                                <div class="relative -translate-x-1/2 -translate-y-1/2">
                                    <div class="w-6 h-6 rounded-full bg-brand-accent border-2 border-white shadow animate-pulse"></div>
                                    <div class="absolute top-8 left-1/2 -translate-x-1/2 bg-black/60 px-3 py-1 rounded text-xs whitespace-nowrap">
                                        <span x-text="spot.label"></span>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </template>
            </div>
        </template>
    </div>

    <!-- STANDARD TEMPLATE -->
    <template x-if="template === 'default'">
        <div class="relative z-10 flex flex-col items-center justify-center h-full text-center px-4">
            
            <!-- HEADING -->
            <h1 class="text-4xl md:text-6xl font-custom font-bold mb-4 drop-shadow-lg" 
                x-html="heading"
                :style="visible ? 'opacity: 1; transform: translateY(0); transition: all 1s ease-out;' : 'opacity: 0; transform: translateY(50px); transition: all 1s ease-out;'"
                style="opacity: 0; transform: translateY(50px); transition: all 1s ease-out;">
            </h1>
            
            <!-- SUBHEADING -->
            <p class="text-xl md:text-2xl font-light mb-8 drop-shadow-md text-slate-100" 
               x-html="subheading"
               :style="visible ? 'opacity: 1; transform: translateY(0); transition: all 1s ease-out 0.2s;' : 'opacity: 0; transform: translateY(50px); transition: all 1s ease-out 0.2s;'"
               style="opacity: 0; transform: translateY(50px); transition: all 1s ease-out 0.2s;">
            </p>

            <!-- DESCRIPTION -->
            @if(!empty($data['description']))
                <div class="max-w-3xl mb-8 text-lg text-slate-200 drop-shadow-md"
                     :style="visible ? 'opacity: 1; transform: translateY(0); transition: all 1s ease-out 0.4s;' : 'opacity: 0; transform: translateY(50px); transition: all 1s ease-out 0.4s;'"
                     style="opacity: 0; transform: translateY(50px); transition: all 1s ease-out 0.4s;">
                    {{ $data['description'] }}
                </div>
            @endif
            
            <!-- BUTTON -->
            <!-- BUTTON WRAPPER -->
            <div x-show="showButton" style="display: none;"> <!-- Default hidden until Alpine loads -->
                <a :href="buttonUrl" 
                   x-text="buttonText"
                   class="px-8 py-3 bg-brand-accent hover:bg-brand-primary text-white font-bold rounded-full shadow-lg transform hover:scale-105 inline-block"
                   :style="visible ? 'opacity: 1; transform: translateY(0); transition: all 1s ease-out 0.6s;' : 'opacity: 0; transform: translateY(50px); transition: all 1s ease-out 0.6s;'"
                   style="opacity: 0; transform: translateY(50px); transition: all 1s ease-out 0.6s;">
                </a>
            </div>
        </div>
    </template>

    <!-- HOTSPOTS TEMPLATE -->
    <template x-if="template === 'hotspots'">
        <div class="relative z-20 w-full h-full">
            <div class="absolute inset-0 flex flex-col items-center justify-center text-center pointer-events-none px-4 z-10">
                <!-- HEADING -->
                <h2 class="text-4xl md:text-6xl font-bold mb-4 drop-shadow-lg" 
                    :style="visible ? 'opacity: 1; transform: translateY(0); transition: all 1s ease-out;' : 'opacity: 0; transform: translateY(50px); transition: all 1s ease-out;'"
                    style="opacity: 0; transform: translateY(50px); transition: all 1s ease-out;">
                    {!! $heading !!}
                </h2>

                <!-- SUBHEADING -->
                <p class="text-xl md:text-2xl font-light mb-6 drop-shadow-md text-slate-100" 
                   :style="visible ? 'opacity: 1; transform: translateY(0); transition: all 1s ease-out 0.2s;' : 'opacity: 0; transform: translateY(50px); transition: all 1s ease-out 0.2s;'"
                   style="opacity: 0; transform: translateY(50px); transition: all 1s ease-out 0.2s;">
                   {!! $subheading !!}
                </p>

                <!-- DESCRIPTION -->
                @if(!empty($data['description']))
                    <p class="max-w-2xl text-lg text-slate-200 drop-shadow-sm" 
                       :style="visible ? 'opacity: 1; transform: translateY(0); transition: all 1s ease-out 0.4s;' : 'opacity: 0; transform: translateY(50px); transition: all 1s ease-out 0.4s;'"
                       style="opacity: 0; transform: translateY(50px); transition: all 1s ease-out 0.4s;">
                       {!! nl2br(e($data['description'])) !!}
                    </p>
                @endif
            </div>
        </div>
    </template>
</div>
