@props(['posts'])

@php
    // Normalize posts to array structure if specific keys are missing or if it's a collection
    $normalizedPosts = [];
    $rawPosts = $posts instanceof \Illuminate\Support\Eloquent\Collection ? $posts : collect($posts);
    
    foreach ($rawPosts as $post) {
        if ($post instanceof \App\Models\Blog) {
             $normalizedPosts[] = [
                'title' => $post->title,
                'image' => $post->thumbnail_path ? \Illuminate\Support\Facades\Storage::url($post->thumbnail_path) : 'https://placehold.co/800x600?text=' . urlencode($post->title),
                'date_day' => $post->created_at->format('d'),
                'date_month' => $post->created_at->format('M'),
                'author' => $post->author_name ?? 'Admin',
                'category' => $post->category ?? 'Travel',
                'excerpt' => $post->excerpt,
                'slug' => $post->slug,
            ];
        } else {
             $normalizedPosts[] = $post;
        }
    }
    
    // Default fallback if empty
    if (empty($normalizedPosts)) {
         $normalizedPosts = [
            [
                'title' => 'Get Best Advertiser In Your Side Pocket',
                'type' => 'featured',
                'image' => 'https://placehold.co/800x600?text=Bromo+Sunrise',
                'date_day' => '22',
                'date_month' => 'Feb',
                'author' => 'Admin',
                'category' => 'Agency',
                'excerpt' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.'
            ],
            [
                'title' => 'Top 10 Tips for Climbing Ijen Crater Safely',
                'type' => 'standard',
                'image' => 'https://placehold.co/400x300?text=Ijen+Hike',
                'date_day' => '10',
                'date_month' => 'Mar',
                'author' => 'Admin',
                'category' => 'Travel',
                'excerpt' => ''
            ],
            [
                'title' => 'Hidden Gems in East Java You Must Visit',
                'type' => 'standard',
                'image' => 'https://placehold.co/400x300?text=East+Java',
                'date_day' => '03',
                'date_month' => 'Jun',
                'author' => 'Admin',
                'category' => 'Tour',
                'excerpt' => ''
            ]
        ];
    }
@endphp

<section class="py-16 md:py-20 bg-white relative font-sans overflow-hidden">
    
    <div class="container mx-auto px-6 md:px-12 lg:px-20 relative z-10">
        
        {{-- Section Header --}}
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-10 gap-4">
            <div class="text-left">
                <span class="inline-block bg-orange-100 text-orange-500 font-hand text-xl px-4 py-1 rounded-full mb-2 transform -rotate-1">
                    Blog & News
                </span>
                <h2 class="text-4xl md:text-5xl font-extrabold text-brand-dark tracking-tight leading-tight">
                    Explore Blogs <span class="text-brand-primary font-hand italic">And News</span>
                </h2>
            </div>
            
            <a href="{{ route('blogs.index') }}" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-full border border-slate-200 text-brand-dark font-bold hover:bg-brand-primary hover:text-white hover:border-brand-primary transition-all group self-start md:self-end text-sm">
                See More Article
                <div class="w-6 h-6 rounded-full bg-brand-primary text-white flex items-center justify-center group-hover:bg-white group-hover:text-brand-primary transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3.5 h-3.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                    </svg>
                </div>
            </a>
        </div>

        {{-- Blog Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach(array_slice($normalizedPosts, 0, 3) as $post)
                <div class="group h-full flex flex-col bg-white rounded-3xl overflow-hidden shadow-[0_10px_40px_-5px_rgba(0,0,0,0.05)] border border-slate-100 hover:shadow-[0_20px_50px_-5px_rgba(0,0,0,0.1)] transition-all duration-300 hover:-translate-y-1">
                    {{-- Image Container --}}
                    <div class="relative w-full aspect-[16/10] overflow-hidden">
                        <img src="{{ $post['image'] }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                        
                        {{-- Overlay --}}
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-60 group-hover:opacity-40 transition-opacity"></div>

                        {{-- Date Badge --}}
                        <div class="absolute top-4 left-4 bg-white/90 backdrop-blur-sm text-brand-dark w-12 h-14 rounded-xl flex flex-col items-center justify-center shadow-lg border border-white/50">
                            <span class="text-lg font-extrabold leading-none">{{ $post['date_day'] }}</span>
                            <span class="text-[10px] font-bold uppercase tracking-wider text-brand-primary">{{ $post['date_month'] }}</span>
                        </div>

                        {{-- Category Badge --}}
                        <div class="absolute top-4 right-4">
                            <span class="inline-block bg-brand-primary text-white text-[10px] font-bold px-3 py-1.5 rounded-full uppercase tracking-wider shadow-lg">
                                {{ $post['category'] }}
                            </span>
                        </div>
                    </div>

                    {{-- Content --}}
                    <div class="p-6 flex-1 flex flex-col">
                        {{-- Meta --}}
                        <div class="flex items-center gap-2 text-xs text-slate-400 mb-3 font-semibold uppercase tracking-wider">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-brand-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span>{{ $post['author'] }}</span>
                            <span class="w-1 h-1 rounded-full bg-slate-300"></span>
                            <span>5 Min Read</span>
                        </div>

                        <h3 class="text-xl font-bold text-brand-dark mb-3 group-hover:text-brand-primary transition-colors leading-snug">
                            <a href="{{ isset($post['slug']) ? route('blogs.show', $post['slug']) : '#' }}" class="focus:outline-none">
                                {{ $post['title'] }}
                            </a>
                        </h3>
                        
                        @if(!empty($post['excerpt']))
                            <p class="text-slate-500 text-sm mb-5 leading-relaxed line-clamp-3">
                                {{ $post['excerpt'] }}
                            </p>
                        @endif

                        <div class="mt-auto pt-4 border-t border-slate-100 flex items-center justify-between">
                            <a href="{{ isset($post['slug']) ? route('blogs.show', $post['slug']) : '#' }}" class="inline-flex items-center gap-2 text-brand-dark font-bold text-sm group/link hover:text-brand-primary transition-colors">
                                Read Article
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 transition-transform group-hover/link:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        </div>
    </div>
</section>
