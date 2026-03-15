<?php
    $seo = new \App\Models\SeoMetadata([
        'meta_title' => $category->name . ' Tour Packages',
        'meta_description' => $category->description ?? 'Browse affordable ' . $category->name . ' tour packages with private transport & English-speaking guides. Free pickup from hotels in East Java & Bali.',
    ]);
?>

<x-app-layout :seo="$seo">
    {{-- Hero Section --}}
    <div class="relative pt-24 pb-12 md:pt-32 md:pb-20 lg:pt-48 lg:pb-32 overflow-hidden">
        <div class="absolute inset-0 bg-brand-dark">
            <img src="{{ asset('images/heroes/hero-1_optimized.webp') }}" alt="{{ $category->name }}" class="w-full h-full object-cover opacity-40">
            <div class="absolute inset-0 bg-gradient-to-t from-brand-dark to-transparent"></div>
        </div>
        
        <div class="container mx-auto px-6 md:px-12 lg:px-20 relative z-10 text-center">
            <span class="inline-block bg-white/10 backdrop-blur-md text-white border border-white/20 font-hand text-xl px-4 py-1 rounded-full mb-4 transform -rotate-1">
                {{ $category->name }}
            </span>
            <h1 class="text-4xl md:text-6xl font-extrabold text-white tracking-tight leading-tight mb-6">
                {{ $category->name }} <span class="text-brand-primary font-hand italic">Packages</span>
            </h1>
            @if($category->description)
                <p class="text-slate-300 text-lg max-w-2xl mx-auto">{{ $category->description }}</p>
            @endif
        </div>
    </div>

    {{-- Category Tabs --}}
    <section class="bg-slate-50 border-b border-slate-200">
        <div class="container mx-auto px-6 md:px-12 lg:px-20">
            <div class="flex flex-wrap items-center gap-2 py-4 overflow-x-auto">
                <a href="{{ route('packages.index') }}" 
                   class="px-5 py-2 rounded-full text-sm font-bold transition-all duration-300 bg-slate-100 text-slate-600 hover:bg-slate-200 whitespace-nowrap">
                    All Packages
                </a>
                @foreach($categories as $cat)
                    <a href="{{ route('packages.category', $cat->slug) }}" 
                       class="px-5 py-2 rounded-full text-sm font-bold transition-all duration-300 whitespace-nowrap {{ $cat->id === $category->id ? 'bg-brand-primary text-white shadow-lg shadow-brand-primary/30' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                        {{ $cat->name }}
                        @if($cat->published_packages_count > 0)
                            <span class="ml-1 text-xs opacity-75">({{ $cat->published_packages_count }})</span>
                        @endif
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Package Grid Section --}}
    <section class="py-16 md:py-24 bg-white relative">
        <div class="container mx-auto px-6 md:px-12 lg:px-20">

            @if($packages->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 md:gap-8 mb-12">
                    @foreach($packages as $package)
                        <div class="group flex flex-col h-full bg-white rounded-3xl overflow-hidden shadow-[0_2px_20px_rgba(0,0,0,0.05)] border border-slate-100 hover:shadow-xl transition-all duration-300">
                            {{-- Image --}}
                            <div class="relative aspect-[4/3] overflow-hidden">
                                <a href="{{ route('packages.show', $package->slug) }}">
                                    @php $catThumb = $package->thumbnail ?? $package->destination->image_path; $catMed = \App\Services\ImageOptimizationService::getMediumUrl($catThumb); @endphp
                                    <img src="{{ $catMed ?? Storage::url($catThumb) }}" 
                                         @if($catMed) srcset="{{ $catMed }} 600w, {{ Storage::url($catThumb) }} 1080w" sizes="(max-width: 768px) 100vw, (max-width: 1024px) 50vw, 33vw" @endif
                                         alt="{{ $package->name }}" loading="lazy"
                                         class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                                </a>
                                
                                {{-- Price Badge --}}
                                <div class="absolute top-4 left-4 bg-brand-accent text-white px-4 py-2 rounded-xl flex flex-col items-center justify-center shadow-lg">
                                    <span class="text-[11px] uppercase font-bold opacity-90">Start From</span>
                                    <span class="text-sm font-bold">IDR {{ number_format($package->price_start_from, 0, ',', '.') }}</span>
                                </div>

                                {{-- Duration Badge --}}
                                <div class="absolute top-4 right-4">
                                     <span class="bg-white/90 backdrop-blur-sm text-brand-dark text-xs font-bold px-3 py-1.5 rounded-full shadow-sm flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 text-brand-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        {{ $package->duration_days }} Days
                                     </span>
                                </div>
                            </div>

                            {{-- Content --}}
                            <div class="p-8 flex-1 flex flex-col">
                                <div class="flex items-center gap-2 text-xs text-slate-500 mb-4">
                                    <span class="flex items-center gap-1 text-brand-primary font-bold uppercase tracking-wider">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        {{ $package->destination->name ?? 'East Java' }}
                                    </span>
                                </div>

                                <h3 class="text-xl font-extrabold text-brand-dark mb-4 group-hover:text-brand-primary transition-colors leading-tight line-clamp-2">
                                    <a href="{{ route('packages.show', $package->slug) }}">
                                        {{ $package->name }}
                                    </a>
                                </h3>

                                <p class="text-slate-500 text-sm mb-6 leading-relaxed line-clamp-3">
                                    {{ Str::limit(strip_tags($package->short_description), 120) }}
                                </p>

                                <div class="mt-auto pt-6 border-t border-slate-50 flex items-center justify-between">
                                    <a href="{{ route('packages.show', $package->slug) }}" class="inline-flex items-center gap-2 text-brand-primary font-bold text-sm group/link">
                                        View Details
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 transition-transform group-hover/link:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                                    </a>
                                    
                                    @if($package->rating > 0)
                                    <div class="flex items-center gap-1 text-yellow-500 text-sm font-bold">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 fill-current" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                        {{ $package->rating }}
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

            @else
                <div class="text-center py-20 bg-slate-50 rounded-3xl border border-slate-100">
                    <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-700">No packages in this category yet</h3>
                    <p class="text-slate-500 mb-6">We are currently curating new {{ $category->name }} experiences.</p>
                    <a href="{{ route('packages.index') }}" class="inline-flex items-center gap-2 text-brand-primary font-bold">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18" /></svg>
                        View All Packages
                    </a>
                </div>
            @endif

        </div>
    </section>
</x-app-layout>
