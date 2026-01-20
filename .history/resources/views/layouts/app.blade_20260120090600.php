@props(['seo' => null])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth overflow-x-hidden">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @php
        $settings = \App\Models\Setting::pluck('value', 'key');
        $siteName = $settings['site_name'] ?? config('app.name', 'Bromo Ijen Expedition');
        $favicon = $settings['favicon'] ?? null;
        $logo = $settings['site_logo'] ?? null;
        $providerName = $settings['provider_name'] ?? 'Premium Expeditions';
        $memberSince = $settings['member_since'] ?? date('Y');
        // Verification & Scripts
        $googleVerify = $settings['google_verification_code'] ?? null;
        $bingVerify = $settings['bing_verification_code'] ?? null;
        $headerCode = $settings['site_header_code'] ?? null;
        $footerCode = $settings['site_footer_code'] ?? null;
    // Global SEO
        $defaultOg = $settings['default_og_image'] ?? null;

        // Header CTA Button
        $headerBtnShow = ($settings['header_button_show'] ?? 'true') === 'true';
        $headerBtnText = $settings['header_button_text'] ?? 'Get in Touch';
        $headerBtnUrl = $settings['header_button_url'] ?? '#book';
        $headerBtnIcon = $settings['header_button_icon'] ?? '';
        $headerBtnIconPos = $settings['header_button_icon_position'] ?? 'left';
        
        // Dynamic Navigation (Recursive Loading)
        $navMenus = \App\Models\NavigationMenu::whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->with(['children' => function($query) {
                $query->where('is_active', true)->orderBy('sort_order')
                      ->with(['children' => function($q) {
                          $q->where('is_active', true)->orderBy('sort_order')
                            ->with(['children' => function($q2) {
                                $q2->where('is_active', true)->orderBy('sort_order')
                                   ->with(['children' => function($q3) {
                                       $q3->where('is_active', true)->orderBy('sort_order');
                                   }]);
                            }]);
                      }]);
            }])
            ->get();
    @endphp

    {{-- SEO Metadata --}}
    @if(isset($seo))
        @if($seo instanceof \Illuminate\View\ComponentSlot)
            {{ $seo }}
        @else
            <title>{{ $seo->meta_title }} | {{ $siteName }}</title>
            <meta name="description" content="{{ $seo->meta_description }}">
            <meta name="keywords" content="{{ $seo->meta_keywords }}">
            <meta property="og:title" content="{{ $seo->meta_title }}">
            <meta property="og:description" content="{{ $seo->meta_description }}">
            @if(!empty($seo->og_image))
                <meta property="og:image" content="{{ \Illuminate\Support\Facades\Storage::url($seo->og_image) }}">
            @elseif(!empty($defaultOg))
                <meta property="og:image" content="{{ \Illuminate\Support\Facades\Storage::url($defaultOg) }}">
            @endif
        @endif
    @else
        <title>{{ $siteName }}</title>
        @if(!empty($defaultOg))
            <meta property="og:image" content="{{ \Illuminate\Support\Facades\Storage::url($defaultOg) }}">
        @endif
    @endif

    @if(!empty($favicon))
        @php $favUrl = \Illuminate\Support\Facades\Storage::url($favicon); @endphp
        <link rel="icon" href="{{ $favUrl }}?v={{ md5($favicon) }}">
        <link rel="apple-touch-icon" href="{{ $favUrl }}?v={{ md5($favicon) }}">
    @endif

    {{-- Verification Tags --}}
    @if($googleVerify)
        {!! $googleVerify !!}
    @endif
    @if($bingVerify)
        <meta name="msvalidate.01" content="{{ $bingVerify }}" />
    @endif

    {{-- Custom Header Scripts --}}
    @if($headerCode)
        {!! $headerCode !!}
    @endif

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Just+Another+Hand&display=swap" rel="stylesheet">
    
    {{-- Swiper CSS --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    {{-- Scripts --}}
    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        // Polyfill/Alias for components expecting SwiperModules
        window.SwiperModules = [Swiper.Navigation, Swiper.Pagination, Swiper.Autoplay, Swiper.EffectFade];
    </script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'brand-primary': '#63ab45',
                        'brand-accent': '#f7921e',
                        'brand-dark': '#1d231f',
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                        hand: ['"Just Another Hand"', 'cursive'],
                    }
                }
            }
        }
    </script>
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="font-sans antialiased text-brand-dark bg-white selection:bg-brand-accent selection:text-white overflow-x-hidden">
    
    {{-- Navbar (Gotur Style) --}}
    <nav x-data="{ scrolled: false }" 
         @scroll.window="scrolled = (window.pageYOffset > 20)"
         :class="{ 
            'bg-slate-900/95 backdrop-blur-sm shadow-lg': scrolled, 
            'bg-transparent': !scrolled && {{ request()->routeIs('home') ? 'true' : 'false' }},
            'bg-slate-900': !scrolled && !{{ request()->routeIs('home') ? 'true' : 'false' }}
         }"
         class="fixed w-full z-50 transition-all duration-300 border-b border-white/5 font-sans">
        <div class="container mx-auto px-6 md:px-12 lg:px-20">
            <div class="flex justify-between items-center h-20">
                <a href="{{ route('home') }}" class="flex items-center gap-2 text-2xl font-extrabold tracking-tighter text-white">
                    @if(!empty($logo))
                        <img src="{{ \Illuminate\Support\Facades\Storage::url($logo) }}" alt="{{ $siteName }}" class="h-10 w-auto">
                    @else
                        {{ strtoupper($siteName) }}
                    @endif
                </a>

                <div class="hidden md:flex space-x-6 items-center">
                    @foreach($navMenus as $menu)
                        <x-navigation-item :menu="$menu" :level="0" />
                    @endforeach
                </div>

                @if($headerBtnShow)
                <div class="hidden md:block">
                    <a href="{{ $headerBtnUrl }}" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-full bg-brand-accent hover:bg-white hover:text-brand-accent text-white transition font-bold shadow-lg border-2 border-transparent hover:border-brand-accent">
                        @if($headerBtnIcon && $headerBtnIconPos === 'left')
                            @include('components.icons.' . $headerBtnIcon, ['class' => 'w-4 h-4'])
                        @endif
                        <span>{{ $headerBtnText }}</span>
                        @if($headerBtnIcon && $headerBtnIconPos === 'right')
                            @include('components.icons.' . $headerBtnIcon, ['class' => 'w-4 h-4'])
                        @endif
                    </a>
                </div>
                @endif
            </div>
        </div>
    </nav>

    {{-- Main Content --}}
    <main>
        {{ $slot }}
    </main>

    {{-- Footer --}}
    <footer class="bg-brand-dark text-white py-12 mt-auto">
        <div class="container-custom text-center">
            <h3 class="text-xl font-bold mb-4">
                 @if(!empty($logo))
                    <img src="{{ \Illuminate\Support\Facades\Storage::url($logo) }}" alt="{{ $siteName }}" class="h-8 w-auto mx-auto grayscale opacity-80 hover:grayscale-0 hover:opacity-100 transition">
                @else
                    {{ strtoupper($siteName) }}
                @endif
            </h3>
            <p class="text-slate-400 text-sm">&copy; {{ date('Y') }} {{ $providerName }}. All rights reserved.</p>
        </div>
    </footer>

    @if($footerCode)
        {!! $footerCode !!}
    @endif

    @if(request()->query('editor_mode') === 'true')
    <script>
        console.log('Visual Editor Mode Active');
        
        window.addEventListener('message', function(event) {
            const data = event.data;
            
            if (data.type === 'UPDATE_BLOCK_FIELD') {
                const { index, field, value } = data;
                
                // Find the block container
                const blockContainer = document.querySelector(`[data-block-index="${index}"]`);
                if (!blockContainer) return;

                // Find the specific field within the block
                const element = blockContainer.querySelector(`[data-field="${field}"]`);
                if (!element) return;

                // Update Logic
                if (element.tagName === 'IMG' || element.tagName === 'VIDEO' || element.tagName === 'SOURCE') {
                    element.src = value;
                    if(element.tagName === 'VIDEO') element.load(); // Reload video if source changes
                } else {
                    element.innerHTML = value;
                }
            } else if (data.type === 'SCROLL_TO_BLOCK') {
                const blockContainer = document.querySelector(`[data-block-index="${data.index}"]`);
                if (blockContainer) {
                    blockContainer.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    // Highlight effect
                    blockContainer.style.outline = '2px solid #3b82f6';
                    blockContainer.style.position = 'relative';
                    blockContainer.style.zIndex = '100';
                    setTimeout(() => {
                        blockContainer.style.outline = 'none';
                        blockContainer.style.zIndex = '';
                    }, 2000);
                }
            }
        });

        // Disable link clicks in editor mode to prevent navigating away
        document.addEventListener('click', function(e) {
            if (e.target.closest('a')) {
                e.preventDefault();
            }
        });
    </script>
    {{-- Global Image Debugger --}}
    <script>
        document.addEventListener('error', function(e) {
            if (e.target.tagName === 'IMG') {
                console.group('[Global Image Debug] ❌ Image Failed to Load');
                console.error('Source:', e.target.src);
                console.error('Alt Text:', e.target.alt);
                console.error('Classes:', e.target.className);
                console.error('HTML:', e.target.outerHTML);
                console.error('Parent Class:', e.target.parentElement.className);
                console.groupEnd();
                // Visual Indicator
                e.target.style.border = '4px solid red';
                e.target.style.opacity = '0.5';
            }
        }, true); // Capture phase to catch error events on img elements
    </script>
    @endif

    @stack('scripts')
</body>
</html>
