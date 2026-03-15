@php
    $blogSeo = $blog->seo ?? new \App\Models\SeoMetadata([
        'meta_title' => $blog->title,
        'meta_description' => \Illuminate\Support\Str::limit(strip_tags($blog->content ?? ''), 160),
        'og_type' => 'article',
    ]);
    if (!$blogSeo->meta_title) $blogSeo->meta_title = $blog->title;
    if (!$blogSeo->meta_description) $blogSeo->meta_description = \Illuminate\Support\Str::limit(strip_tags($blog->content ?? ''), 160);
    $blogSeo->og_type = 'article';
@endphp
<x-app-layout :seo="$blogSeo">

    @push('structured-data')
    {{-- BreadcrumbList --}}
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "BreadcrumbList",
        "itemListElement": [
            { "@type": "ListItem", "position": 1, "name": "Home", "item": "{{ route('home') }}" },
            { "@type": "ListItem", "position": 2, "name": "Blog", "item": "{{ route('blogs.index') }}" },
            { "@type": "ListItem", "position": 3, "name": "{{ $blog->title }}" }
        ]
    }
    </script>
    {{-- BlogPosting --}}
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "BlogPosting",
        "headline": "{{ $blog->title }}",
        "description": "{{ $blogSeo->meta_description }}",
        "url": "{{ url()->current() }}",
        "inLanguage": "en",
        @if($blog->thumbnail_path)"image": "{{ asset('storage/' . $blog->thumbnail_path) }}",@endif
        "datePublished": "{{ $blog->published_at?->toIso8601String() ?? $blog->created_at?->toIso8601String() }}",
        "dateModified": "{{ $blog->updated_at?->toIso8601String() }}",
        "wordCount": {{ str_word_count(strip_tags($blog->body ?? '')) }},
        @if($blog->category)"articleSection": "{{ $blog->category->name }}",@endif
        @if($blogSeo->meta_keywords)"keywords": "{{ $blogSeo->meta_keywords }}",@endif
        "author": {
            "@type": "Organization",
            "name": "Bromo Ijen Expedition Java",
            "url": "{{ url('/') }}"
        },
        "publisher": {
            "@type": "Organization",
            "name": "Bromo Ijen Expedition Java",
            "url": "{{ url('/') }}"
        },
        "mainEntityOfPage": {
            "@type": "WebPage",
            "@id": "{{ url()->current() }}"
        }
    }
    </script>
    {{-- FAQPage (if article contains FAQ section) --}}
    @php
        $faqItems = [];
        if (preg_match_all('/<h3>([^<]+)<\/h3>\s*<p>([^<]+(?:<[^>]+>[^<]*<\/[^>]+>)*[^<]*)<\/p>/s', $blog->body ?? '', $matches, PREG_SET_ORDER)) {
            $inFaq = false;
            foreach ($matches as $m) {
                $question = trim(strip_tags($m[1]));
                $answer = trim(strip_tags($m[2]));
                if (str_contains($question, '?')) {
                    $inFaq = true;
                    $faqItems[] = ['q' => $question, 'a' => $answer];
                }
            }
        }
    @endphp
    @if(count($faqItems) >= 2)
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "FAQPage",
        "mainEntity": [
            @foreach($faqItems as $faq)
            {
                "@type": "Question",
                "name": "{{ addslashes($faq['q']) }}",
                "acceptedAnswer": {
                    "@type": "Answer",
                    "text": "{{ addslashes($faq['a']) }}"
                }
            }@if(!$loop->last),@endif
            @endforeach
        ]
    }
    </script>
    @endif
    @endpush

    {{-- Hero Image --}}
    <div class="relative h-[45vh] min-h-[320px] md:h-[55vh] md:min-h-[450px] overflow-hidden">
        <img src="{{ $blog->thumbnail_path ? asset('storage/' . $blog->thumbnail_path) : 'https://placehold.co/1920x800?text=' . urlencode($blog->title) }}" 
             alt="{{ $blog->title }}"
             class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-black/20"></div>
        
        <div class="absolute bottom-0 left-0 w-full p-6 md:p-12 lg:p-20 pt-24">
            <div class="container mx-auto">
                <div class="max-w-4xl">
                     <span class="inline-block bg-brand-primary text-white text-xs font-bold px-3 py-1.5 rounded-full mb-4 uppercase tracking-wider">
                        {{ $blog->category->name ?? 'Uncategorized' }}
                     </span>
                    <h1 class="text-3xl md:text-5xl lg:text-6xl font-extrabold text-white leading-tight mb-6">
                        {{ $blog->title }}
                    </h1>
                    
                    <div class="flex flex-wrap items-center gap-6 text-slate-300 text-sm font-medium">
                        <div class="flex items-center gap-2">
                             <img src="https://ui-avatars.com/api/?name={{ urlencode($blog->author->name ?? 'Admin') }}&background=random" class="w-8 h-8 rounded-full border border-white/20">
                             <span>{{ $blog->author->name ?? 'Admin' }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                            <span>{{ $blog->published_at->format('d M, Y') }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                            <span>{{ $blog->view_count }} views</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Content Section --}}
    <section class="py-16 md:py-24 bg-white relative">
        <div class="container mx-auto px-6 md:px-12 lg:px-20">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
                
                {{-- Main Content --}}
                <div class="lg:col-span-8">
                    <article class="prose prose-lg prose-slate max-w-none first-letter:text-5xl first-letter:font-bold first-letter:text-brand-primary first-letter:mr-3 first-letter:float-left">
                        {!! $blog->body !!}
                    </article>

                    {{-- Tags & Share --}}
                    <div class="mt-12 pt-8 border-t border-slate-100 flex flex-col md:flex-row items-center justify-between gap-6">
                        <div class="flex items-center gap-2">
                            <span class="font-bold text-brand-dark">Share this story:</span>
                            <div class="flex gap-2">
                                <a href="#" class="w-11 h-11 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 hover:bg-[#1877F2] hover:text-white transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.791-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                </a>
                                <a href="#" class="w-11 h-11 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 hover:bg-[#1DA1F2] hover:text-white transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>
                                </a>
                                <a href="#" class="w-11 h-11 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 hover:bg-[#25D366] hover:text-white transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.52.151-.174.2-.298.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Sidebar / Related --}}
                <div class="lg:col-span-4">
                    <div class="sticky top-20">
                        {{-- CTA --}}
                        <div class="relative rounded-3xl overflow-hidden aspect-[4/5] group mb-8">
                            <img src="https://placehold.co/600x800?text=Bromo+Tour" loading="lazy" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-black/40 group-hover:bg-black/50 transition-colors"></div>
                            <div class="absolute inset-x-0 bottom-0 p-8 text-center">
                                <h3 class="text-2xl font-extrabold text-white mb-2">Ready to explore?</h3>
                                <p class="text-white/80 text-sm mb-6">Book your private guided trip to Bromo today.</p>
                                 <a href="{{ route('packages.index') }}" class="inline-block w-full py-3 bg-brand-primary text-white font-bold rounded-full hover:bg-white hover:text-brand-primary transition-all">
                                    View Packages
                                </a>
                            </div>
                        </div>

                        {{-- Related Articles --}}
                        @php
                            $moreBlogs = \App\Models\Blog::with(['author', 'category'])
                                ->whereNotNull('published_at')
                                ->where('id', '!=', $blog->id)
                                ->inRandomOrder()
                                ->take(3)
                                ->get();
                        @endphp
                        @if($moreBlogs->count())
                        <div class="bg-slate-50 p-8 rounded-3xl border border-slate-100 mt-8">
                            <h3 class="text-xl font-bold text-brand-dark mb-6">Related Articles</h3>
                            <div class="flex flex-col divide-y divide-slate-200">
                                @foreach($moreBlogs as $moreBlog)
                                    <a href="{{ route('blogs.show', $moreBlog->slug) }}" class="group flex gap-4 items-start py-4 first:pt-0 last:pb-0">
                                        <div class="w-20 h-20 rounded-xl overflow-hidden shrink-0">
                                            <img src="{{ $moreBlog->thumbnail_path ? asset('storage/' . $moreBlog->thumbnail_path) : 'https://placehold.co/150x150?text=' . urlencode(Str::limit($moreBlog->title, 10)) }}"
                                                 alt="{{ $moreBlog->title }}" loading="lazy"
                                                 class="w-full h-full object-cover group-hover:scale-110 transition-transform">
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <span class="text-[11px] font-bold text-brand-primary uppercase tracking-wider mb-1 block">
                                                {{ $moreBlog->category->name ?? 'News' }}
                                            </span>
                                            <h4 class="font-bold text-slate-700 leading-snug group-hover:text-brand-primary transition-colors line-clamp-2 text-sm">
                                                {{ $moreBlog->title }}
                                            </h4>
                                            <span class="text-xs text-slate-400 mt-1 block">{{ $moreBlog->published_at?->format('d M, Y') }}</span>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                            <a href="{{ route('blogs.index') }}" class="mt-5 inline-flex items-center gap-1 text-sm font-bold text-brand-primary hover:text-brand-dark transition-colors">
                                View All Articles
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4"><path fill-rule="evenodd" d="M3 10a.75.75 0 0 1 .75-.75h10.638L10.23 5.29a.75.75 0 1 1 1.04-1.08l5.5 5.25a.75.75 0 0 1 0 1.08l-5.5 5.25a.75.75 0 1 1-1.04-1.08l4.158-3.96H3.75A.75.75 0 0 1 3 10Z" clip-rule="evenodd" /></svg>
                            </a>
                        </div>
                        @endif

                        {{-- Recommended Packages --}}
                        @php
                            $recPackages = \App\Models\Package::with(['destination'])
                                ->where('status', 'published')
                                ->inRandomOrder()
                                ->take(3)
                                ->get();
                        @endphp
                        @if($recPackages->count())
                        <div class="bg-slate-50 p-8 rounded-3xl border border-slate-100 mt-8">
                            <h3 class="text-xl font-bold text-brand-dark mb-6">Popular Packages</h3>
                            <div class="flex flex-col gap-4">
                                @foreach($recPackages as $recPkg)
                                <a href="{{ route('packages.show', $recPkg->slug) }}" class="group flex gap-3 items-start bg-white rounded-xl border border-slate-100 p-3 hover:shadow-md transition-all">
                                    <div class="w-20 h-16 rounded-lg overflow-hidden shrink-0">
                                        @php
                                            $recThumb = $recPkg->thumbnail;
                                            if(!empty($recThumb) && !str_starts_with($recThumb, 'http')) {
                                                $recThumb = \Illuminate\Support\Facades\Storage::url($recThumb);
                                            } elseif(empty($recThumb)) {
                                                $recThumb = 'https://placehold.co/150x120?text=Tour';
                                            }
                                        @endphp
                                        <img src="{{ $recThumb }}" alt="{{ $recPkg->name }}" loading="lazy"
                                             class="w-full h-full object-cover group-hover:scale-110 transition-transform">
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4 class="text-sm font-bold text-slate-700 leading-snug group-hover:text-brand-primary transition-colors line-clamp-2">
                                            {{ $recPkg->name }}
                                        </h4>
                                        <div class="flex items-center gap-2 mt-1">
                                            <span class="text-xs text-brand-primary font-bold">IDR {{ number_format($recPkg->price_start_from, 0, ',', '.') }}</span>
                                            <span class="text-[11px] text-slate-400">· {{ $recPkg->duration_days }} Days</span>
                                        </div>
                                    </div>
                                </a>
                                @endforeach
                            </div>
                            <a href="{{ route('packages.index') }}" class="mt-5 inline-flex items-center gap-1 text-sm font-bold text-brand-primary hover:text-brand-dark transition-colors">
                                View All Packages
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4"><path fill-rule="evenodd" d="M3 10a.75.75 0 0 1 .75-.75h10.638L10.23 5.29a.75.75 0 1 1 1.04-1.08l5.5 5.25a.75.75 0 0 1 0 1.08l-5.5 5.25a.75.75 0 1 1-1.04-1.08l4.158-3.96H3.75A.75.75 0 0 1 3 10Z" clip-rule="evenodd" /></svg>
                            </a>
                        </div>
                        @endif
                    
                    </div>
                </div>

            </div>
        </div>
    </section>
</x-app-layout>
