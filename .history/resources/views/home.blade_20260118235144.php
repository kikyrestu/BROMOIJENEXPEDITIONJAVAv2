<x-app-layout :seo="$page->seo ?? null">
    
    {{-- Hero Section --}}
    @php
        $heroData = [];
        $aboutData = [];
        $destinationsData = []; // Existing destinations passed via controller
        $galleryData = [];
        $testimonialsData = [];
        $blogNewsData = [];

        if ($page && is_array($page->content)) {
            $blocks = collect($page->content);
            $heroData = $blocks->firstWhere('type', 'hero_video')['data'] ?? [];
            $aboutData = $blocks->firstWhere('type', 'about_us')['data'] ?? [];
            $galleryData = $blocks->firstWhere('type', 'gallery')['data'] ?? [];
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
    <x-sections.exclusive-destinations :destinations="$destinations" />

    {{-- 3. Gallery --}}
    <x-sections.gallery :data="$galleryData" />

    {{-- 4. Packages --}}
    <x-sections.package-slider :packages="$packages" />

    {{-- 5. Testimonials (Marquee) --}}
    @if(!empty($testimonialsData['testimonials']))
         <x-sections.testimonials-marquee :testimonials="$testimonialsData['testimonials']" />
    @else
         <x-sections.testimonials-marquee />
    @endif

    {{-- 7. Blog / News --}}
    @if(isset($latest_posts) && $latest_posts->count() > 0 && ($blogNewsData['auto_fetch'] ?? true))
        <x-sections.blog-news :posts="$latest_posts" />
    @elseif(!empty($blogNewsData['posts']))
        <x-sections.blog-news :posts="$blogNewsData['posts']" />
    @else
        <x-sections.blog-news />
    @endif

</x-app-layout>
