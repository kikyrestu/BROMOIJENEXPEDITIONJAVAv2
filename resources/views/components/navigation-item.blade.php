@props(['menu', 'level' => 0])

@php
    $effectiveChildren = $menu->effectiveChildren;
    $hasChildren = $effectiveChildren->count() > 0;
@endphp

@if($level === 0)
    {{-- Level 0: Top Header Menu --}}
    @if($hasChildren)
        <div class="relative"
             x-data="{ open: false, timer: null }"
             @mouseenter="clearTimeout(timer); open = true"
             @mouseleave="timer = setTimeout(() => open = false, 150)">
            <button @click="open = !open"
                    class="flex items-center gap-1 text-sm font-bold text-white hover:text-brand-accent transition py-2 px-1 whitespace-nowrap">
                {{ $menu->name }}
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3 h-3 transition-transform duration-200" :class="{'rotate-180': open}">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                </svg>
            </button>
            <div x-show="open"
                 x-transition:enter="transition ease-out duration-100"
                 x-transition:enter-start="transform opacity-0 scale-95"
                 x-transition:enter-end="transform opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-75"
                 x-transition:leave-start="transform opacity-100 scale-100"
                 x-transition:leave-end="transform opacity-0 scale-95"
                 x-ref="dropdown"
                 class="absolute left-0 mt-0 min-w-[14rem] max-w-[20rem] bg-white rounded-lg shadow-xl py-2 z-50 border border-gray-100 font-normal">
                @foreach($effectiveChildren as $child)
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
        <div class="relative group/submenu w-full"
             x-data="{ subOpen: false, subTimer: null }"
             @mouseenter="clearTimeout(subTimer); subOpen = true"
             @mouseleave="subTimer = setTimeout(() => subOpen = false, 150)">
            <button class="w-full flex items-center justify-between px-4 py-2 text-sm text-gray-700 hover:bg-brand-primary hover:text-white transition-colors text-left">
                <span>{{ $menu->name }}</span>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3 h-3 ml-3 flex-shrink-0">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                </svg>
            </button>

            <!-- Submenu Flyout (Right side, repositions if near edge) -->
            <div x-show="subOpen"
                 x-transition:enter="transition ease-out duration-100"
                 x-transition:enter-start="transform opacity-0 translate-x-1"
                 x-transition:enter-end="transform opacity-100 translate-x-0"
                 x-transition:leave="transition ease-in duration-75"
                 x-transition:leave-start="transform opacity-100 translate-x-0"
                 x-transition:leave-end="transform opacity-0 translate-x-1"
                 x-ref="submenu"
                 class="absolute left-full top-0 min-w-[14rem] max-w-[20rem] bg-white rounded-lg shadow-xl py-2 z-[60] border border-gray-100"
                 x-cloak>
                @foreach($effectiveChildren as $child)
                    <x-navigation-item :menu="$child" :level="$level + 1" />
                @endforeach
            </div>
        </div>
    @else
        <a href="{{ $menu->link }}" target="{{ $menu->target }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-brand-primary hover:text-white transition-colors">
            {{ $menu->name }}
        </a>
    @endif
@endif
