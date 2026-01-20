<x-app-layout>
    <x-slot:seo>
        @if($package->seo)
            <title>{{ $package->seo->meta_title }}</title>
            <meta name="description" content="{{ $package->seo->meta_description }}">
        @else
            <title>{{ $package->name }} - Bromo Ijen Expedition</title>
        @endif
    </x-slot:seo>

    <div class="pt-32 pb-20 container mx-auto px-6 md:px-12 lg:px-20 font-sans text-slate-600">
        
        {{-- TOP HEADER: Title & Share --}}
        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-6 mb-8">
            <div>
                 <h1 class="text-3xl md:text-5xl font-extrabold text-brand-dark leading-tight mb-2">
                    {{ $package->name }}
                </h1>
                <div class="flex items-center gap-2 text-sm text-slate-500">
                     <span class="text-brand-accent font-bold">(17 Review)</span>
                     <div class="flex text-brand-accent text-xs">
                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                     </div>
                     <span class="mx-2">|</span>
                     <span class="flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-brand-primary" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
                        East Java, Indonesia
                     </span>
                </div>
            </div>
            
            <button class="px-6 py-2.5 rounded-lg border border-slate-300 text-brand-dark font-bold hover:bg-brand-primary hover:text-white hover:border-brand-primary transition-all flex items-center gap-2">
                Share
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" /></svg>
            </button>
        </div>

        {{-- INFO BAR (With Green Price Button) --}}
        <div class="bg-white border-t border-b border-slate-100 py-6 mb-12">
            <div class="flex flex-col lg:flex-row items-center justify-between gap-6">
                <div class="flex flex-wrap items-center gap-8 md:gap-16 w-full lg:w-auto">
                    {{-- Location --}}
                    <div class="flex gap-4 items-center">
                        <div class="w-10 h-10 rounded-full bg-brand-accent/10 flex items-center justify-center text-brand-accent">
                             <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                        </div>
                        <div>
                            <span class="block text-xs font-bold text-brand-dark">Location</span>
                            <span class="text-sm text-slate-500">East Java</span>
                        </div>
                    </div>
                    {{-- Activities Type --}}
                    <div class="flex gap-4 items-center">
                        <div class="w-10 h-10 rounded-full bg-brand-accent/10 flex items-center justify-center text-brand-accent">
                             <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                        </div>
                        <div>
                            <span class="block text-xs font-bold text-brand-dark">Activities Type</span>
                            <span class="text-sm text-slate-500">Adventure</span>
                        </div>
                    </div>
                    {{-- Activate Day --}}
                    <div class="flex gap-4 items-center">
                        <div class="w-10 h-10 rounded-full bg-brand-accent/10 flex items-center justify-center text-brand-accent">
                             <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>
                        <div>
                            <span class="block text-xs font-bold text-brand-dark">Duration</span>
                            <span class="text-sm text-slate-500">{{ $package->duration_days }} Days</span>
                        </div>
                    </div>
                </div>

                <div class="w-full lg:w-auto">
                    <button class="w-full lg:w-auto bg-brand-primary text-white font-bold py-3 px-8 rounded-lg text-lg shadow-lg hover:bg-green-600 transition-colors">
                        IDR {{ number_format($package->price_start_from/1000, 0) }}k <span class="text-xs font-normal opacity-80">/Per Person</span>
                    </button>
                </div>
            </div>
        </div>

        {{-- MAIN LAYOUT: Content Left, Sidebar Right --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12 text-slate-500">
            
            {{-- LEFT CONTENT --}}
            <div class="lg:col-span-2 space-y-10">
                
                {{-- TOP GALLERY (Hidden in screenshot but needed for context) --}}
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 h-[400px] rounded-2xl overflow-hidden mb-8">
                     <div class="md:col-span-3 h-full relative group">
                        <img src="{{ $package->destination->thumbnail_path ? asset('storage/'.$package->destination->thumbnail_path) : 'https://placehold.co/1200x800' }}" 
                             class="w-full h-full object-cover">
                    </div>
                    <div class="hidden md:flex flex-col gap-4 h-full">
                        <div class="flex-1 relative"><img src="https://placehold.co/600x400?text=1" class="w-full h-full object-cover"></div>
                        <div class="flex-1 relative"><img src="https://placehold.co/600x400?text=2" class="w-full h-full object-cover"></div>
                    </div>
                </div>

                {{-- Overview --}}
                <section>
                    <h2 class="text-2xl font-extrabold text-brand-dark mb-4">Overview</h2>
                    <div class="leading-relaxed">
                         {{ $package->destination->description ?? 'Join us on an amazing adventure to ' . $package->name . '. We provide professional guides, comfortable transport, and premium service.' }}
                    </div>
                </section>

                {{-- Highlight List (Green Checks) --}}
                <section>
                    <h2 class="text-2xl font-extrabold text-brand-dark mb-4">Highlight List</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                         <div class="flex items-center gap-3">
                             <div class="w-5 h-5 rounded-full border border-brand-primary flex items-center justify-center text-brand-primary text-xs"><i class="fas fa-check"></i></div>
                             <span>Sunrise at Viewpoint</span>
                         </div>
                         <div class="flex items-center gap-3">
                             <div class="w-5 h-5 rounded-full border border-brand-primary flex items-center justify-center text-brand-primary text-xs"><i class="fas fa-check"></i></div>
                             <span>Jeep Adventure 4x4</span>
                         </div>
                         <div class="flex items-center gap-3">
                             <div class="w-5 h-5 rounded-full border border-brand-primary flex items-center justify-center text-brand-primary text-xs"><i class="fas fa-check"></i></div>
                             <span>Professional Photographer</span>
                         </div>
                         <div class="flex items-center gap-3">
                             <div class="w-5 h-5 rounded-full border border-brand-primary flex items-center justify-center text-brand-primary text-xs"><i class="fas fa-check"></i></div>
                             <span>Breakfast with View</span>
                         </div>
                    </div>
                </section>

                {{-- Tour Amenities (Included / Excluded) --}}
                <section>
                    <h2 class="text-2xl font-extrabold text-brand-dark mb-4">Tour Amenities</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 border-t border-b border-slate-100 py-6">
                        {{-- Included --}}
                        <div class="space-y-3">
                            @if($package->inclusions)
                                {!! preg_replace('/<ul>/', '<ul class="space-y-3">', str_replace('<li>', '<li class="flex items-center gap-3 text-sm font-bold text-slate-600"><span class="text-green-500 font-extrabold text-lg">✓</span>', $package->inclusions)) !!}
                            @else
                                <div class="flex items-center gap-3"><span class="text-green-500 font-bold">✓</span> <span class="font-bold text-slate-600">Jeep 4x4</span></div>
                                <div class="flex items-center gap-3"><span class="text-green-500 font-bold">✓</span> <span class="font-bold text-slate-600">Driver & Fuel</span></div>
                                <div class="flex items-center gap-3"><span class="text-green-500 font-bold">✓</span> <span class="font-bold text-slate-600">Mineral Water</span></div>
                            @endif
                        </div>
                        {{-- Excluded --}}
                        <div class="space-y-3">
                             @if($package->exclusions)
                                {!! preg_replace('/<ul>/', '<ul class="space-y-3">', str_replace('<li>', '<li class="flex items-center gap-3 text-sm font-bold text-slate-600"><span class="text-red-500 font-extrabold text-lg">✕</span>', $package->exclusions)) !!}
                            @else
                                <div class="flex items-center gap-3"><span class="text-red-500 font-bold">✕</span> <span class="font-bold text-slate-600">Personal Expenses</span></div>
                                <div class="flex items-center gap-3"><span class="text-red-500 font-bold">✕</span> <span class="font-bold text-slate-600">Travel Insurance</span></div>
                            @endif
                        </div>
                    </div>
                </section>

                {{-- Two Images (Visual) --}}
                <div class="grid grid-cols-2 gap-6">
                    <img src="https://placehold.co/600x400?text=Amenities+1" class="w-full rounded-2xl">
                    <img src="https://placehold.co/600x400?text=Amenities+2" class="w-full rounded-2xl">
                </div>

                {{-- Tour Plan (Accordion) --}}
                <section x-data="{ activeDay: 0 }">
                    <h2 class="text-2xl font-extrabold text-brand-dark mb-6">Tour Plan</h2>
                    <div class="space-y-4">
                        @foreach($package->itinerary ?? [] as $index => $item)
                            <div class="border border-slate-100 rounded-xl bg-white shadow-sm">
                                <button @click="activeDay = (activeDay === {{ $index }} ? null : {{ $index }})" 
                                        class="w-full flex items-center justify-between p-5 text-left">
                                    <div class="flex items-center gap-2">
                                        <span class="text-brand-primary font-bold">Day {{ $index + 1 }}</span>
                                        <span class="font-bold text-brand-dark">{{ $item['title'] ?? 'Activity' }}</span>
                                    </div>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-400 transition-transform" :class="activeDay === {{ $index }} ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                </button>
                                <div x-show="activeDay === {{ $index }}" x-collapse class="px-5 pb-5 pt-0 text-sm text-slate-500">
                                    @if(isset($item['description']))
                                        <p class="mb-2">{{ $item['description'] }}</p>
                                    @endif

                                    @if(isset($item['activities']) && is_array($item['activities']))
                                        <ul class="space-y-2">
                                            @foreach($item['activities'] as $activity)
                                                <li class="flex gap-3">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-brand-primary mt-1.5 flex-shrink-0"></span>
                                                    <span>{{ $activity }}</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>

            </div>

             {{-- SIDEBAR --}}
            <div class="hidden lg:block relative col-span-1">
                <div class="sticky top-28 space-y-6">
                    
                    {{-- Booking Card --}}
                    <div class="border border-slate-200 rounded-2xl p-8 bg-white shadow-sm">
                        <h3 class="text-xl font-extrabold text-brand-dark mb-6">Book This Tour</h3>
                        
                        {{-- Form Fields --}}
                        <div class="space-y-4 mb-6">
                            {{-- From Date --}}
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-2">From:</label>
                                <div class="relative">
                                    <input type="text" placeholder="Select Date" class="w-full border border-slate-200 rounded-lg py-3 px-4 text-sm focus:outline-none focus:border-brand-primary bg-slate-50">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-brand-accent absolute right-3 top-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                </div>
                            </div>
                            
                            {{-- Time --}}
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-2">Time:</label>
                                <select class="w-full border border-slate-200 rounded-lg py-3 px-4 text-sm focus:outline-none focus:border-brand-primary bg-slate-50 text-slate-500">
                                    <option>Select Time</option>
                                    <option>08:00 AM</option>
                                </select>
                            </div>

                            {{-- Tickets --}}
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-2">Tickets:</label>
                                <div class="text-sm font-bold text-slate-400 bg-slate-50 py-3 px-4 rounded-lg border border-slate-200">
                                    Please, Select Date First
                                </div>
                            </div>

                            {{-- Add Extra --}}
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-2">Add Extra:</label>
                                <div class="space-y-2">
                                    <label class="flex items-center gap-2 text-sm text-slate-500 cursor-pointer">
                                        <input type="checkbox" class="rounded border-slate-300 text-brand-primary focus:ring-brand-primary">
                                        Services Per Booking
                                    </label>
                                    <label class="flex items-center gap-2 text-sm text-slate-500 cursor-pointer">
                                        <input type="checkbox" class="rounded border-slate-300 text-brand-primary focus:ring-brand-primary">
                                        Services Per Person
                                    </label>
                                </div>
                            </div>
                        </div>

                        {{-- Total & Button --}}
                        <div class="border-t border-slate-100 pt-6">
                            <div class="flex justify-between items-center mb-2 text-sm">
                                <span>Adult:</span>
                                <span class="font-bold">IDR {{ number_format($package->price_start_from/1000, 0) }}k</span>
                            </div>
                             <div class="flex justify-between items-center mb-6 text-lg font-extrabold text-brand-dark">
                                <span>Total:</span>
                                <span>IDR {{ number_format($package->price_start_from/1000, 0) }}k</span>
                            </div>
                            
                             <a href="{{ \App\Helpers\WhatsAppLinkGenerator::generate($package, 'desktop-sidebar') }}" 
                               target="_blank"
                               class="block w-full py-4 bg-brand-primary text-white font-bold rounded-full text-center hover:bg-green-600 transition-colors">
                                Book Now ->
                            </a>
                        </div>
                    </div>

                    {{-- Map Widget --}}
                    <div class="rounded-2xl overflow-hidden relative h-64 border border-slate-200 shadow-sm cursor-pointer group">
                        <img src="https://placehold.co/400x400?text=Map+Location" class="w-full h-full object-cover">
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="w-12 h-12 bg-red-500 rounded-full flex items-center justify-center text-white shadow-lg group-hover:scale-110 transition-transform">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>
