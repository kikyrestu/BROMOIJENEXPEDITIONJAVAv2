<x-app-layout>
    {{-- Hero Section --}}
    <div class="relative pt-32 pb-20 lg:pt-48 lg:pb-32 overflow-hidden">
        <div class="absolute inset-0 bg-brand-dark">
            <img src="https://placehold.co/1920x600?text=Blog+Header" class="w-full h-full object-cover opacity-40">
            <div class="absolute inset-0 bg-gradient-to-t from-brand-dark to-transparent"></div>
        </div>
        
        <div class="container mx-auto px-6 md:px-12 lg:px-20 relative z-10 text-center">
            <span class="inline-block bg-white/10 backdrop-blur-md text-white border border-white/20 font-hand text-xl px-4 py-1 rounded-full mb-4 transform -rotate-1">
                Our Journal
            </span>
            <h1 class="text-4xl md:text-6xl font-extrabold text-white tracking-tight leading-tight mb-6">
                Travel Stories & <span class="text-brand-primary font-hand italic">Tips</span>
            </h1>
            <p class="text-slate-300 text-lg max-w-2xl mx-auto">
                Discover the hidden gems of East Java, travel guides, and photography tips from our experienced guides.
            </p>
        </div>
    </div>

    {{-- Blog Grid Section --}}
    <section class="py-16 md:py-24 bg-white relative">
        <div class="container mx-auto px-6 md:px-12 lg:px-20">
            
            {{-- Banner: Blog Hero --}}
            <x-banner-spot location="blog_hero" />

            @if($blogs->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
                    @foreach($blogs as $blog)
                        <div class="group flex flex-col h-full bg-white rounded-3xl overflow-hidden shadow-[0_2px_20px_rgba(0,0,0,0.05)] border border-slate-100 hover:shadow-xl transition-all duration-300">
                            {{-- Image --}}
                            <div class="relative aspect-[4/3] overflow-hidden">
                                <a href="{{ route('blogs.show', $blog->slug) }}">
                                    <img src="{{ $blog->thumbnail_path ? asset('storage/' . $blog->thumbnail_path) : 'https://placehold.co/800x600?text=' . urlencode($blog->title) }}" 
                                         alt="{{ $blog->title }}"
                                         class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                                </a>
                                
                                {{-- Date Badge --}}
                                <div class="absolute top-4 left-4 bg-brand-primary text-white w-14 h-16 rounded-xl flex flex-col items-center justify-center shadow-lg">
                                    <span class="text-xl font-bold leading-none">{{ $blog->published_at->format('d') }}</span>
                                    <span class="text-xs font-medium uppercase">{{ $blog->published_at->format('M') }}</span>
                                </div>

                                {{-- Category Badge --}}
                                <div class="absolute top-4 right-4">
                                     <span class="bg-white/90 backdrop-blur-sm text-brand-dark text-xs font-bold px-3 py-1.5 rounded-full shadow-sm">
                                        {{ $blog->category->name ?? 'Uncategorized' }}
                                     </span>
                                </div>
                            </div>

                            {{-- Content --}}
                            <div class="p-8 flex-1 flex flex-col">
                                <div class="flex items-center gap-2 text-xs text-slate-500 mb-4">
                                    <span class="flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-brand-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                        {{ $blog->author->name ?? 'Admin' }}
                                    </span>
                                    <span>&bull;</span>
                                    <span>{{ $blog->view_count }} views</span>
                                </div>

                                <h3 class="text-xl font-extrabold text-brand-dark mb-4 group-hover:text-brand-primary transition-colors leading-tight line-clamp-2">
                                    <a href="{{ route('blogs.show', $blog->slug) }}">
                                        {{ $blog->title }}
                                    </a>
                                </h3>

                                <p class="text-slate-500 text-sm mb-6 leading-relaxed line-clamp-3">
                                    {{ Str::limit(strip_tags($blog->body), 120) }}
                                </p>

                                <a href="{{ route('blogs.show', $blog->slug) }}" class="inline-flex items-center gap-2 text-brand-primary font-bold mt-auto text-sm group/link">
                                    Read Article
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 transition-transform group-hover/link:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="mt-12">
                    {{ $blogs->links() }}
                </div>
            @else
                <div class="text-center py-20 bg-slate-50 rounded-3xl border border-slate-100">
                    <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-700">No stories found</h3>
                    <p class="text-slate-500">We are currently crafting new travel stories. Check back soon!</p>
                </div>
            @endif

            {{-- Banner: Blog Footer --}}
            <x-banner-spot location="blog_footer" />

        </div>
    </section>
</x-app-layout>
