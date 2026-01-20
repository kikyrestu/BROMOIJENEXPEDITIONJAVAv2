@props(['menu', 'level' => 0])

@php
    $hasChildren = $menu->children->count() > 0;
    // Determine positioning style based on level
    // Level 0: Top Horizontal Bar (Not handled here usually, but if so...)
    // Actually this component will handle Level 1+ items (the items in the dropdown or the top level items)
    // Let's assume this component is used INSIDE the loops.
    // If level 0 (Top Bar), standard text.
    // If level > 0 (Dropdown Item), different styling.
@endphp

@if($level === 0)
    {{-- Level 0: Top Header Menu --}}
    @if($hasChildren)
        <div class="relative group" x-data="{ open: false }">
            <button @click="open = !open" 
                    @mouseenter="open = true" 
                    @mouseleave="open = false" 
                    class="flex items-center gap-1 text-sm font-bold text-white hover:text-brand-accent transition py-2 px-1 whitespace-nowrap">
                {{ $menu->name }}
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3 h-3 transition-transform duration-200" :class="{'rotate-180': open}">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                </svg>
            </button>
            <div x-show="open" 
                 @mouseenter="open = true" 
                 @mouseleave="open = false"
                 x-transition:enter="transition ease-out duration-100"
                 x-transition:enter-start="transform opacity-0 scale-95"
                 x-transition:enter-end="transform opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-75"
                 x-transition:leave-start="transform opacity-100 scale-100"
                 x-transition:leave-end="transform opacity-0 scale-95"
                 class="absolute left-0 mt-0 min-w-[12rem] w-max bg-white rounded-lg shadow-xl py-2 z-50 border border-gray-100 font-normal">
                @foreach($menu->children as $child)
                    <x-navigation-item :menu="$child" :level="$level + 1" />
                @endforeach
            </div>
        </div>
    @else
        <a href="{{ $menu->link }}" target="{{ $menu->target }}" class="text-sm font-bold text-white hover:text-brand-accent transition px-1 whitespace-nowrap">
            {{ $menu->name }}
        </a>
    @endif

@else
    {{-- Level 1+: Dropdown Items --}}
    @if($hasChildren)
        <div class="relative group/submenu w-full" x-data="{ subOpen: false }">
            <button @click="subOpen = !subOpen" 
                    @mouseenter="subOpen = true" 
                    @mouseleave="subOpen = false" 
                    class="w-full flex items-center justify-between px-4 py-2 text-sm text-gray-700 hover:bg-brand-primary hover:text-white transition-colors text-left whitespace-nowrap">
                <span>{{ $menu->name }}</span>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3 h-3 -rotate-90">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                </svg>
            </button>

            <!-- Submenu Flyout (Right side) -->
            <div x-show="subOpen" 
                 @mouseenter="subOpen = true" 
                 @mouseleave="subOpen = false"
                 x-transition:enter="transition ease-out duration-100"
                 x-transition:enter-start="transform opacity-0 translate-x-2"
                 x-transition:enter-end="transform opacity-100 translate-x-0"
                 class="absolute left-full top-0 ml-1 min-w-[12rem] w-max bg-white rounded-lg shadow-xl py-2 z-50 border border-gray-100" 
                 style="display: none;">
                @foreach($menu->children as $child)
                    <x-navigation-item :menu="$child" :level="$level + 1" />
                @endforeach
            </div>
        </div>
    @else
        <a href="{{ $menu->link }}" target="{{ $menu->target }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-brand-primary hover:text-white transition-colors whitespace-nowrap">
            {{ $menu->name }}
        </a>
    @endif
@endif
