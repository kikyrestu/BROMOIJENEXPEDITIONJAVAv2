<x-app-layout :seo="$page->seo ?? null">
    
    {{-- Hero Section --}}
    @php
        $heroData = [];
        if ($page && is_array($page->content)) {
            $heroBlock = collect($page->content)->firstWhere('type', 'hero_video');
            $heroData = $heroBlock['data'] ?? [];
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
    <x-sections.about-us />

    {{-- 2. Exclusive Destinations --}}
    <x-sections.exclusive-destinations :destinations="$destinations" />

    {{-- 3. Gallery --}}
    <x-sections.gallery />

    {{-- 4. Packages --}}
    <x-sections.package-slider :packages="$packages" />

    {{-- 5. Testimonials (Marquee) --}}
    <x-sections.testimonials-marquee />

    {{-- 7. Blog / News --}}
    @if(isset($latest_posts) && $latest_posts->count() > 0)
        <x-sections.blog-news :posts="$latest_posts" />
    @else
        <x-sections.blog-news />
    @endif

</x-app-layout>
