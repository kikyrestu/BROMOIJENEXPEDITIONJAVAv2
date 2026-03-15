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
        $providerName = $settings['provider_name'] ?? 'Bromo Ijen Expedition';
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
    @php
        $seoTitle = $siteName;
        $seoDesc = $settings['default_meta_description'] ?? 'Book private tours to Mount Bromo, Ijen Crater & Tumpak Sewu from $50/person. Trusted by 500+ travelers. Free hotel pickup in East Java & Bali.';
        $seoKeywords = '';
        $seoImage = !empty($defaultOg) ? \Illuminate\Support\Facades\Storage::url($defaultOg) : '';
        $seoCanonical = url()->current();
        $seoOgType = 'website';
    @endphp
    @if(isset($seo))
        @if($seo instanceof \Illuminate\View\ComponentSlot)
            {{ $seo }}
        @else
            @php
                $seoTitle = ($seo->meta_title ? $seo->meta_title . ' | ' : '') . $siteName;
                $seoDesc = $seo->meta_description ?: $seoDesc;
                $seoKeywords = $seo->meta_keywords ?? '';
                if(!empty($seo->og_image)) $seoImage = \Illuminate\Support\Facades\Storage::url($seo->og_image);
                if(!empty($seo->canonical_url)) $seoCanonical = $seo->canonical_url;
                $seoOgType = $seo->og_type ?? 'website';
            @endphp
        @endif
    @endif
    <title>{{ $seoTitle }}</title>
    <meta name="description" content="{{ $seoDesc }}">
    @if($seoKeywords)<meta name="keywords" content="{{ $seoKeywords }}">@endif
    <link rel="canonical" href="{{ $seoCanonical }}">

    {{-- International SEO: hreflang --}}
    <link rel="alternate" hreflang="en" href="{{ $seoCanonical }}">
    <link rel="alternate" hreflang="x-default" href="{{ $seoCanonical }}">

    {{-- Open Graph --}}
    <meta property="og:type" content="{{ $seoOgType }}">
    <meta property="og:locale" content="en_US">
    <meta property="og:site_name" content="{{ $siteName }}">
    <meta property="og:title" content="{{ $seoTitle }}">
    <meta property="og:description" content="{{ $seoDesc }}">
    <meta property="og:url" content="{{ url()->current() }}">
    @if($seoImage)<meta property="og:image" content="{{ $seoImage }}">@endif

    {{-- Twitter Cards --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $seoTitle }}">
    <meta name="twitter:description" content="{{ $seoDesc }}">
    @if($seoImage)<meta name="twitter:image" content="{{ $seoImage }}">@endif

    {{-- JSON-LD Structured Data: Organization --}}
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "TravelAgency",
        "name": "{{ $siteName }}",
        "url": "{{ $settings['site_url'] ?? url('/') }}",
        "inLanguage": "en",
        "availableLanguage": ["English", "Indonesian"],
        @if(!empty($logo))"logo": "{{ \Illuminate\Support\Facades\Storage::url($logo) }}",@endif
        @if(!empty($settings['provider_phone']))"telephone": "+{{ $settings['provider_phone'] }}",@endif
        @if(!empty($settings['provider_email']))"email": "{{ $settings['provider_email'] }}",@endif
        "address": {
            "@type": "PostalAddress",
            "addressLocality": "Probolinggo",
            "addressRegion": "East Java",
            "addressCountry": "ID"
        },
        "geo": {
            "@type": "GeoCoordinates",
            "latitude": "-7.9425",
            "longitude": "112.9530"
        },
        "priceRange": "$$",
        "description": "{{ $seoDesc }}",
        "sameAs": [
            @php
                $socialLinksData = $settings['social_links'] ?? '[]';
                $socials = is_string($socialLinksData) ? json_decode($socialLinksData, true) : (is_array($socialLinksData) ? $socialLinksData : []);
            @endphp
            @foreach($socials ?? [] as $social)
                "{{ $social['url'] ?? '' }}"@if(!$loop->last),@endif
            @endforeach
        ]
    }
    </script>

    {{-- JSON-LD: WebSite (homepage only) --}}
    @if(request()->routeIs('home'))
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "WebSite",
        "name": "{{ $siteName }}",
        "url": "{{ $settings['site_url'] ?? url('/') }}",
        "inLanguage": "en",
        "potentialAction": {
            "@type": "SearchAction",
            "target": "{{ url('/blogs') }}?q={search_term_string}",
            "query-input": "required name=search_term_string"
        }
    }
    </script>
    @endif

    {{-- Page-specific structured data (breadcrumbs, schemas) pushed from views --}}
    @stack('structured-data')

    {{-- Favicons (static square PNGs for Google Search compatibility) --}}
    <link rel="icon" type="image/png" sizes="48x48" href="/favicon-48.png">
    <link rel="icon" type="image/png" sizes="192x192" href="/favicon-192.png">
    <link rel="apple-touch-icon" sizes="192x192" href="/favicon-192.png">

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

    {{-- Fonts (non-render-blocking with font-display: swap) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Just+Another+Hand&display=swap">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Just+Another+Hand&display=swap" rel="stylesheet" media="print" onload="this.media='all'">

    {{-- Vite Build (CSS + JS bundled - MUCH faster than CDN) --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased text-brand-dark bg-white selection:bg-brand-accent selection:text-white overflow-x-hidden">
    
    {{-- Navbar (Gotur Style) --}}
    <nav x-data="{ scrolled: false, mobileMenuOpen: false }" 
         @scroll.window="scrolled = (window.pageYOffset > 20)"
         :class="{ 
            'bg-slate-900/95 backdrop-blur-sm shadow-lg': scrolled, 
            'bg-transparent': !scrolled && {{ request()->routeIs('home') ? 'true' : 'false' }},
            'bg-slate-900': !scrolled && !{{ request()->routeIs('home') ? 'true' : 'false' }}
         }"
         class="fixed w-full z-50 transition-all duration-300 border-b border-white/5 font-sans">
        <div class="container mx-auto px-4 sm:px-6 md:px-12 lg:px-20">
            <div class="flex justify-between items-center h-16 md:h-20">
                {{-- Logo --}}
                <a href="{{ route('home') }}" class="flex items-center gap-2 text-xl md:text-2xl font-extrabold tracking-tighter text-white">
                    @if(!empty($logo))
                        <img src="{{ \Illuminate\Support\Facades\Storage::url($logo) }}" alt="{{ $siteName }}" class="h-8 md:h-10 w-auto">
                    @else
                        {{ strtoupper($siteName) }}
                    @endif
                </a>

                {{-- Desktop Navigation --}}
                <div class="hidden lg:flex space-x-6 items-center">
                    @foreach($navMenus as $menu)
                        <x-navigation-item :menu="$menu" :level="0" />
                    @endforeach
                </div>

                {{-- Desktop CTA Button --}}
                @if($headerBtnShow)
                <div class="hidden lg:block">
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

                {{-- Mobile Menu Button --}}
                <button @click="mobileMenuOpen = !mobileMenuOpen" 
                        class="lg:hidden flex items-center justify-center w-11 h-11 rounded-lg text-white hover:bg-white/10 transition-colors"
                        :aria-expanded="mobileMenuOpen"
                        aria-label="Toggle mobile menu">
                    <svg x-show="!mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    <svg x-show="mobileMenuOpen" x-cloak class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        {{-- Mobile Menu Drawer --}}
        <div x-show="mobileMenuOpen"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-4"
             x-cloak
             @click.outside="mobileMenuOpen = false"
             class="lg:hidden absolute top-full left-0 w-full bg-slate-900/98 backdrop-blur-lg border-t border-white/10 shadow-2xl max-h-[80vh] overflow-y-auto">
            <div class="container mx-auto px-4 py-6 space-y-2">
                @foreach($navMenus as $menu)
                    <div x-data="{ open: false }" class="border-b border-white/5 last:border-0">
                        @if($menu->effectiveChildren->count() > 0)
                            <button @click="open = !open" 
                                    class="flex items-center justify-between w-full py-3 text-white hover:text-brand-accent transition-colors text-left">
                                <span class="font-semibold">{{ $menu->name }}</span>
                                <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <div x-show="open" x-collapse class="pl-4 pb-3 space-y-1">
                                @foreach($menu->effectiveChildren as $child)
                                    @if($child->effectiveChildren->count() > 0)
                                        <div x-data="{ subOpen: false }">
                                            <button @click="subOpen = !subOpen" 
                                                    class="flex items-center justify-between w-full py-2 text-white/70 hover:text-brand-accent transition-colors text-sm text-left">
                                                <span>{{ $child->name }}</span>
                                                <svg class="w-3 h-3 transition-transform" :class="{ 'rotate-180': subOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                                </svg>
                                            </button>
                                            <div x-show="subOpen" x-collapse class="pl-4 space-y-1">
                                                @foreach($child->effectiveChildren as $subChild)
                                                    <a href="{{ $subChild->link }}" 
                                                       @click="mobileMenuOpen = false"
                                                       class="block py-1.5 text-white/50 hover:text-brand-accent transition-colors text-xs">
                                                        {{ $subChild->name }}
                                                    </a>
                                                @endforeach
                                            </div>
                                        </div>
                                    @else
                                        <a href="{{ $child->link }}" 
                                           @click="mobileMenuOpen = false"
                                           class="block py-2 text-white/70 hover:text-brand-accent transition-colors text-sm">
                                            {{ $child->name }}
                                        </a>
                                    @endif
                                @endforeach
                            </div>
                        @else
                            <a href="{{ $menu->link }}" 
                               @click="mobileMenuOpen = false"
                               class="block py-3 text-white hover:text-brand-accent transition-colors font-semibold">
                                {{ $menu->name }}
                            </a>
                        @endif
                    </div>
                @endforeach
                
                {{-- Mobile CTA Button --}}
                @if($headerBtnShow)
                <div class="pt-4">
                    <a href="{{ $headerBtnUrl }}" 
                       @click="mobileMenuOpen = false"
                       class="flex items-center justify-center gap-2 w-full px-6 py-3 rounded-full bg-brand-accent hover:bg-brand-accent/90 text-white transition font-bold shadow-lg">
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

    {{-- Floating Social Bar + Back to Top --}}
    @php
        $floatingSocialRaw = $settings['floating_social_enabled'] ?? '1';
        $floatingSocialEnabled = in_array($floatingSocialRaw, ['1', 'true', true, 1], true);
        $socialLinks = $settings['social_links'] ?? [];
        if (is_string($socialLinks)) {
            $socialLinks = json_decode($socialLinks, true) ?? [];
        }
    @endphp
    @if($floatingSocialEnabled)
        <x-floating-social-bar :socials="$socialLinks" :showBackToTop="true" />
    @endif

    {{-- Footer --}}
    @php
        $footerSocials = is_string($socialLinks) ? json_decode($socialLinks, true) : $socialLinks;
        $footerSocials = $footerSocials ?? [];
    @endphp
    <footer class="bg-gradient-to-b from-slate-900 to-slate-950 text-white">
        {{-- Main Footer Content --}}
        <div class="w-full max-w-screen-2xl mx-auto px-6 md:px-12 lg:px-16 py-12 md:py-16">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-12">
                
                {{-- Column 1: Brand & About --}}
                <div class="sm:col-span-2 lg:col-span-1">
                    <div class="mb-6">
                        @if(!empty($logo))
                            <img src="{{ \Illuminate\Support\Facades\Storage::url($logo) }}" alt="{{ $siteName }}" class="h-10 w-auto brightness-0 invert opacity-90">
                        @else
                            <h3 class="text-2xl font-bold text-white">{{ $siteName }}</h3>
                        @endif
                    </div>
                    <p class="text-slate-400 text-sm leading-relaxed mb-6">
                        Experience the majestic beauty of East Java with our expert-guided expedition services. Trusted by hundreds of adventurers since {{ $memberSince }}.
                    </p>
                    {{-- Social Icons --}}
                    @if(count($footerSocials) > 0)
                    <div class="flex gap-3">
                        @foreach($footerSocials as $social)
                        <a href="{{ $social['url'] ?? '#' }}" target="_blank" rel="noopener" 
                           class="w-9 h-9 bg-white/10 hover:bg-brand-primary rounded-lg flex items-center justify-center transition-all duration-300 hover:scale-110">
                            @switch($social['platform'] ?? '')
                                @case('facebook')
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                    @break
                                @case('instagram')
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                                    @break
                                @case('twitter')
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                                    @break
                                @case('whatsapp')
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                    @break
                                @default
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                            @endswitch
                        </a>
                        @endforeach
                    </div>
                    @endif
                </div>

                {{-- Column 2: Quick Links --}}
                <div>
                    <h4 class="text-white font-bold text-lg mb-5 flex items-center gap-2">
                        <span class="w-8 h-0.5 bg-brand-primary"></span>
                        Quick Links
                    </h4>
                    <ul class="space-y-3">
                        @foreach([
                            ['label' => 'Home', 'url' => '/'],
                            ['label' => 'Our Packages', 'url' => '/#packages'],
                            ['label' => 'Destinations', 'url' => '/#destinations'],
                            ['label' => 'Gallery', 'url' => '/#gallery'],
                            ['label' => 'Reviews', 'url' => '/reviews'],
                            ['label' => 'About Us', 'url' => '/#aboutus'],
                        ] as $link)
                        <li>
                            <a href="{{ $link['url'] }}" class="text-slate-400 hover:text-brand-primary transition-colors duration-200 flex items-center gap-2 group">
                                <svg class="w-3 h-3 opacity-0 -translate-x-2 group-hover:opacity-100 group-hover:translate-x-0 transition-all" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>
                                <span>{{ $link['label'] }}</span>
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>

                {{-- Column 3: Contact Info --}}
                <div>
                    <h4 class="text-white font-bold text-lg mb-5 flex items-center gap-2">
                        <span class="w-8 h-0.5 bg-brand-primary"></span>
                        Contact Us
                    </h4>
                    <ul class="space-y-4">
                        @if($settings['provider_phone'] ?? false)
                        <li class="flex items-start gap-3">
                            <div class="w-8 h-8 bg-white/10 rounded-lg flex items-center justify-center shrink-0 mt-0.5">
                                <svg class="w-4 h-4 text-brand-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            </div>
                            <div>
                                <p class="text-slate-500 text-xs uppercase tracking-wider">Phone</p>
                                <a href="tel:{{ $settings['provider_phone'] }}" class="text-slate-300 hover:text-brand-primary transition">{{ $settings['provider_phone'] }}</a>
                            </div>
                        </li>
                        @endif
                        @if($settings['provider_email'] ?? false)
                        <li class="flex items-start gap-3">
                            <div class="w-8 h-8 bg-white/10 rounded-lg flex items-center justify-center shrink-0 mt-0.5">
                                <svg class="w-4 h-4 text-brand-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </div>
                            <div>
                                <p class="text-slate-500 text-xs uppercase tracking-wider">Email</p>
                                <a href="mailto:{{ $settings['provider_email'] }}" class="text-slate-300 hover:text-brand-primary transition">{{ $settings['provider_email'] }}</a>
                            </div>
                        </li>
                        @endif
                        <li class="flex items-start gap-3">
                            <div class="w-8 h-8 bg-white/10 rounded-lg flex items-center justify-center shrink-0 mt-0.5">
                                <svg class="w-4 h-4 text-brand-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </div>
                            <div>
                                <p class="text-slate-500 text-xs uppercase tracking-wider">Location</p>
                                <p class="text-slate-300">East Java, Indonesia</p>
                            </div>
                        </li>
                    </ul>
                </div>

                {{-- Column 4: Newsletter --}}
                <div>
                    <h4 class="text-white font-bold text-lg mb-5 flex items-center gap-2">
                        <span class="w-8 h-0.5 bg-brand-primary"></span>
                        Newsletter
                    </h4>
                    <p class="text-slate-400 text-sm mb-4">Subscribe to get special offers and travel tips directly to your inbox.</p>
                    <form class="space-y-3">
                        <div class="relative">
                            <input type="email" placeholder="Your email address" 
                                   class="w-full bg-white/10 border border-white/10 rounded-lg px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-brand-primary focus:ring-1 focus:ring-brand-primary transition">
                        </div>
                        <button type="submit" class="w-full bg-brand-primary hover:bg-brand-dark text-white font-bold py-3 px-4 rounded-lg transition-all duration-300 flex items-center justify-center gap-2 group">
                            <span>Subscribe</span>
                            <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Bottom Bar --}}
        <div class="border-t border-white/10">
            <div class="w-full max-w-screen-2xl mx-auto px-6 md:px-12 lg:px-16 py-6 flex flex-col items-center gap-4 text-center md:flex-row md:justify-between md:text-left">
                <p class="text-slate-500 text-sm">&copy; {{ date('Y') }} {{ $providerName }}. All rights reserved.</p>
                <div class="flex flex-wrap items-center justify-center gap-4 md:gap-6 text-sm">
                    <a href="#" class="text-slate-500 hover:text-white transition">Privacy Policy</a>
                    <a href="#" class="text-slate-500 hover:text-white transition">Terms of Service</a>
                    <a href="#" class="text-slate-500 hover:text-white transition">Cookie Policy</a>
                </div>
            </div>
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
