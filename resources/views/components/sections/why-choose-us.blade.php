@props(['data' => []])
@php
    $items = $data['items'] ?? [];
    $layout = $data['layout'] ?? 'grid-3';
    $cols = ['grid-2' => 'md:grid-cols-2', 'grid-3' => 'md:grid-cols-3', 'grid-4' => 'md:grid-cols-4'][$layout] ?? 'md:grid-cols-3';
@endphp

<section class="py-20 bg-slate-50">
    <div class="max-w-7xl mx-auto px-6">
        <div class="text-center max-w-2xl mx-auto">
            @if($data['badge_text'] ?? null)
                <span class="inline-block px-4 py-1.5 mb-2 text-base font-hand rounded-full bg-orange-100 text-orange-500" data-live="badge_text">{{ $data['badge_text'] }}</span>
            @endif
            
            <h2 class="mt-3 text-3xl md:text-4xl font-display font-black text-slate-900">
                <span data-live="title_prefix">{{ $data['title_prefix'] ?? '' }}</span>
                <span class="text-brand-primary font-hand italic" data-live="title_suffix">{{ $data['title_suffix'] ?? '' }}</span>
            </h2>
            
            @if($data['subtitle'] ?? null)
                <p class="mt-4 text-slate-600" data-live="subtitle">{{ $data['subtitle'] }}</p>
            @endif
        </div>

        <div class="mt-12 grid gap-8 {{ $cols }}">
            @foreach($items as $i => $item)
                <div class="bg-white rounded-2xl p-8 shadow-sm border border-slate-100 hover:shadow-md transition">
                    <div class="w-12 h-12 rounded-xl bg-brand-primary/10 text-brand-primary flex items-center justify-center">
                        @if(in_array($item['icon'] ?? '', ['sparkles', 'shield', 'calendar', 'rocket', 'chat', 'phone', 'envelope'], true))
                            @include('components.icons.' . $item['icon'], ['class' => 'w-6 h-6'])
                        @else
                            @include('components.icons.sparkles', ['class' => 'w-6 h-6'])
                        @endif
                    </div>
                    <h3 class="mt-5 text-lg font-bold text-slate-900" data-live="item_{{ $i }}_title">
                        {{ $item['title'] ?? '' }}
                    </h3>
                    <p class="mt-2 text-sm text-slate-600 leading-relaxed" data-live="item_{{ $i }}_description">
                        {{ $item['description'] ?? '' }}
                    </p>
                </div>
            @endforeach
        </div>
    </div>
</section>