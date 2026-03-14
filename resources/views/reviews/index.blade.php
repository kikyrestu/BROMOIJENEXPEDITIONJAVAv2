<?php
    $seo = new \App\Models\SeoMetadata([
        'meta_title' => 'Customer Reviews | Bromo Ijen Expedition Java',
        'meta_description' => 'Read honest reviews from travelers who experienced our Bromo, Ijen Crater, and Tumpak Sewu tours. Rated ' . ($stats['average'] ?? '5') . '/5 by ' . ($stats['total'] ?? '0') . '+ happy adventurers.',
        'og_title' => 'Customer Reviews | Bromo Ijen Expedition Java',
        'og_description' => 'See what our customers say about their adventure with Bromo Ijen Expedition Java. ' . ($stats['total'] ?? '0') . ' reviews, ' . ($stats['average'] ?? '5') . ' average rating.',
    ]);
?>

<x-app-layout :seo="$seo">

{{-- JSON-LD Structured Data for Reviews (AggregateRating) --}}
@push('structured-data')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "TouristTrip",
    "name": "Bromo Ijen Expedition Java Tours",
    "description": "Premium tour packages to Mount Bromo, Ijen Crater, Tumpak Sewu and Bali from Bromo Ijen Expedition Java.",
    "provider": {
        "@type": "TourOperator",
        "name": "Bromo Ijen Expedition Java",
        "url": "{{ url('/') }}"
    },
    "aggregateRating": {
        "@type": "AggregateRating",
        "ratingValue": "{{ $stats['average'] }}",
        "bestRating": "5",
        "worstRating": "1",
        "ratingCount": "{{ $stats['total'] }}"
    },
    "review": [
        @foreach($reviews->take(10) as $review)
        {
            "@type": "Review",
            "author": {
                "@type": "Person",
                "name": "{{ e($review->name) }}"
            },
            "datePublished": "{{ $review->created_at->toDateString() }}",
            "reviewBody": "{{ e(Str::limit($review->content, 300)) }}",
            "reviewRating": {
                "@type": "Rating",
                "ratingValue": "{{ $review->rating }}",
                "bestRating": "5"
            }
        }@if(!$loop->last),@endif
        @endforeach
    ]
}
</script>
@endpush

    {{-- Hero Section --}}
    <div class="relative pt-32 pb-20 lg:pt-48 lg:pb-32 overflow-hidden">
        <div class="absolute inset-0 bg-brand-dark">
            <img src="{{ asset('images/heroes/hero-3_optimized.webp') }}" alt="Nusa Penida landscape" class="w-full h-full object-cover opacity-40">
            <div class="absolute inset-0 bg-gradient-to-t from-brand-dark to-transparent"></div>
        </div>

        <div class="container mx-auto px-6 md:px-12 lg:px-20 relative z-10 text-center">
            <span class="inline-block bg-white/10 backdrop-blur-md text-white border border-white/20 font-hand text-xl px-4 py-1 rounded-full mb-4 transform -rotate-1">
                Real Stories
            </span>
            <h1 class="text-4xl md:text-6xl font-extrabold text-white tracking-tight leading-tight mb-6">
                Customer <span class="text-brand-accent font-hand italic">Reviews</span>
            </h1>
            <p class="text-slate-300 text-lg max-w-2xl mx-auto mb-8">
                Hear from travelers who explored Bromo, Ijen Crater, and beyond with us
            </p>

            {{-- Rating Summary Badge --}}
            @if($stats['total'] > 0)
            <div class="inline-flex items-center gap-4 bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl px-8 py-4">
                <div class="text-center">
                    <div class="text-4xl font-extrabold text-brand-accent">{{ $stats['average'] }}</div>
                    <div class="flex items-center justify-center gap-0.5 mt-1">
                        @for($i = 1; $i <= 5; $i++)
                            <svg class="w-4 h-4 {{ $i <= round($stats['average']) ? 'text-brand-accent' : 'text-white/30' }}" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        @endfor
                    </div>
                </div>
                <div class="w-px h-12 bg-white/20"></div>
                <div class="text-left">
                    <div class="text-2xl font-bold text-white">{{ $stats['total'] }}</div>
                    <div class="text-sm text-slate-300">Verified Reviews</div>
                </div>
            </div>
            @endif
        </div>
    </div>

    {{-- Rating Distribution + Reviews --}}
    <section class="py-16 bg-white">
        <div class="container mx-auto px-6 md:px-12 lg:px-20">

            {{-- Rating Distribution Bar --}}
            @if($stats['total'] > 0)
            <div class="max-w-2xl mx-auto mb-16 bg-slate-50 rounded-2xl p-8 border border-slate-100">
                <h2 class="text-lg font-bold text-brand-dark mb-6 text-center">Rating Distribution</h2>
                <div class="space-y-3">
                    @foreach($stats['distribution'] as $star => $count)
                        @php $pct = $stats['total'] > 0 ? round(($count / $stats['total']) * 100) : 0; @endphp
                        <div class="flex items-center gap-3">
                            <span class="text-sm font-bold text-slate-600 w-12 flex items-center gap-1">
                                {{ $star }}
                                <svg class="w-3.5 h-3.5 text-brand-accent" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            </span>
                            <div class="flex-1 h-3 bg-slate-200 rounded-full overflow-hidden">
                                <div class="h-full bg-brand-accent rounded-full transition-all duration-700" style="width: {{ $pct }}%"></div>
                            </div>
                            <span class="text-sm text-slate-500 w-16 text-right">{{ $count }} ({{ $pct }}%)</span>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Reviews Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($reviews as $review)
                    <article class="bg-white rounded-2xl border border-slate-100 shadow-[0_2px_20px_rgba(0,0,0,0.04)] p-6 hover:shadow-lg transition-shadow duration-300 flex flex-col" itemscope itemtype="https://schema.org/Review">
                        <meta itemprop="datePublished" content="{{ $review->created_at->toDateString() }}">

                        {{-- Stars --}}
                        <div class="flex items-center gap-1 mb-4" itemprop="reviewRating" itemscope itemtype="https://schema.org/Rating">
                            <meta itemprop="ratingValue" content="{{ $review->rating }}">
                            <meta itemprop="bestRating" content="5">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="w-5 h-5 {{ $i <= $review->rating ? 'text-brand-accent' : 'text-slate-200' }}" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            @endfor
                        </div>

                        {{-- Review Content --}}
                        <blockquote class="flex-1 mb-6">
                            <p itemprop="reviewBody" class="text-slate-600 leading-relaxed text-sm">
                                "{{ $review->content }}"
                            </p>
                        </blockquote>

                        {{-- Author --}}
                        <div class="flex items-center gap-3 pt-4 border-t border-slate-100 mt-auto" itemprop="author" itemscope itemtype="https://schema.org/Person">
                            @php
                                $avatar = $review->photo_path
                                    ? Storage::disk('public')->url($review->photo_path)
                                    : ($review->avatar && !str_contains($review->avatar, 'ui-avatars.com')
                                        ? Storage::disk('public')->url($review->avatar)
                                        : 'https://ui-avatars.com/api/?name=' . urlencode($review->name) . '&background=10b981&color=fff&size=80');
                            @endphp
                            <img src="{{ $avatar }}"
                                 alt="{{ $review->name }}"
                                 class="w-10 h-10 rounded-full object-cover border-2 border-slate-100"
                                 loading="lazy">
                            <div>
                                <div itemprop="name" class="font-bold text-brand-dark text-sm">{{ $review->name }}</div>
                                <div class="text-xs text-slate-400">{{ $review->country ?? $review->role }}</div>
                            </div>
                            <div class="ml-auto text-xs text-slate-300">
                                {{ $review->created_at->format('M Y') }}
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="col-span-full text-center py-20">
                        <div class="text-6xl mb-4">🌟</div>
                        <h3 class="text-xl font-bold text-slate-400 mb-2">No reviews yet</h3>
                        <p class="text-slate-400">Be the first to share your experience!</p>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            @if($reviews->hasPages())
                <div class="mt-12 flex justify-center">
                    {{ $reviews->links() }}
                </div>
            @endif
        </div>
    </section>

    {{-- CTA Section --}}
    <section class="py-16 bg-gradient-to-br from-brand-dark to-slate-900">
        <div class="container mx-auto px-6 md:px-12 lg:px-20 text-center">
            <h2 class="text-3xl md:text-4xl font-extrabold text-white mb-4">Ready for Your Adventure?</h2>
            <p class="text-slate-300 max-w-xl mx-auto mb-8">Join hundreds of happy travelers and create your own unforgettable memories with us.</p>
            <a href="{{ route('packages.index') }}"
               class="inline-flex items-center gap-2 px-8 py-4 rounded-full bg-brand-accent hover:bg-white hover:text-brand-accent text-white transition font-bold shadow-lg border-2 border-transparent hover:border-brand-accent text-lg">
                Explore Tour Packages
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                </svg>
            </a>
        </div>
    </section>

</x-app-layout>
