<x-app-layout :seo="$page->seo ?? null">
    
    {{-- Hero Section --}}
    @php
        $heroData = [];
        $aboutData = [];
        $destinationsData = [];
        $galleryData = [];
        $packagesData = []; // New
        $testimonialsData = [];
        $blogNewsData = [];

        if ($page && is_array($page->content)) {
            $blocks = collect($page->content);
            $heroData = $blocks->firstWhere('type', 'hero_video')['data'] ?? [];
            $aboutData = $blocks->firstWhere('type', 'about_us')['data'] ?? [];
            $destinationsData = $blocks->firstWhere('type', 'exclusive_destinations')['data'] ?? []; // New
            $galleryData = $blocks->firstWhere('type', 'gallery')['data'] ?? [];
            $packagesData = $blocks->firstWhere('type', 'package_slider')['data'] ?? []; // New
            $testimonialsBlock = $blocks->firstWhere('type', 'testimonials_marquee');
            $testimonialsData = $testimonialsBlock['data'] ?? [];
            $blogNewsBlock = $blocks->firstWhere('type', 'blog_news');
            $blogNewsData = $blogNewsBlock['data'] ?? [];
        }
    @endphp

    {{-- Hero Section --}}
    <x-blocks.hero_video :data="array_merge([
        'heading' => $page->title ?? 'EXPLORE EAST JAVA',
        'subheading' => 'Experience the majestic sunrise of Bromo and the blue fire of Ijen Crater with our premium expedition service.', 
        'button_text' => 'Start Adventure',
        'button_url' => '#packages',
        'backgrounds' => [
            [
                'url' => 'https://images.unsplash.com/photo-1548574505-5e239809ee19?ixlib=rb-1.2.1&auto=format&fit=crop&w=1956&q=80',
                'type' => 'image',
                'mime_type' => 'image/jpeg'
            ]
        ]
    ], $heroData)" />

    {{-- 1. About Us --}}
    <x-sections.about-us :data="$aboutData" />

    {{-- 2. Exclusive Destinations --}}
    <x-sections.exclusive-destinations :destinations="$destinations" :data="$destinationsData" />

    {{-- 3. Gallery --}}
    <x-sections.gallery :data="$galleryData" />

    {{-- 4. Packages --}}
    <x-sections.package-slider :packages="$packages" :data="$packagesData" />

    {{-- 5. Testimonials (Marquee) --}}
    @php
        $testimonialsList = $testimonialsData['testimonials'] ?? [];
        // If specific testimonials not set in block, fallback to Database ones passed from Controller if available, 
        // or let the component handle default. 
        // Actually, the component expects 'testimonials' prop. 
        // Let's passed the block data as strict override if present.
    @endphp
    
    @if(!empty($testimonialsList))
         <x-sections.testimonials-marquee :testimonials="$testimonialsList" :data="$testimonialsData" />
    @else
         <x-sections.testimonials-marquee :data="$testimonialsData" /> 
         {{-- Note: Original component had default array in prop. Passing empty here implies using that default? 
            No, if we pass nothing, it uses default. If we pass empty array, it renders empty.
            Better to let component handle fallback if we don't pass 'testimonials' arg.
         --}}
    @endif

    {{-- 7. Blog / News --}}
    @if(isset($latest_posts) && $latest_posts->count() > 0 && ($blogNewsData['auto_fetch'] ?? true))
        <x-sections.blog-news :posts="$latest_posts" :data="$blogNewsData" />
    @elseif(!empty($blogNewsData['posts']))
        <x-sections.blog-news :posts="$blogNewsData['posts']" :data="$blogNewsData" />
    @else
        <x-sections.blog-news :data="$blogNewsData" />
    @endif

</x-app-layout>
