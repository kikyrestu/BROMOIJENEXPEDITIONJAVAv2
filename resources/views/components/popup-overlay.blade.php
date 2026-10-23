@php
    $popup = \App\Models\Banner::query()
        ->where('is_active', true)->where('popup_enabled', true)
        ->where(function ($q) { $q->whereNull('start_date')->orWhere('start_date', '<=', now()); })
        ->where(function ($q) { $q->whereNull('end_date')->orWhere('end_date', '>=', now()); })
        ->orderByDesc('id')->first();

    $currentPath = request()->path() === '/' ? 'home' : request()->path();
    $allowedPages = $popup?->popup_pages ?? ['*'];
    $pageMatch = !$popup || in_array('*', $allowedPages) || in_array($currentPath, $allowedPages);
    $deviceMatch = !$popup || ($popup->popup_device ?? 'all') === 'all';
    $show = $popup && $pageMatch && $deviceMatch;
    $storageKey = 'bie_popup_' . ($popup->slug ?? 'x');
@endphp

@if($show)
<div x-data="popupOverlay({
        delay: {{ (int) ($popup->popup_delay_seconds ?? 3) }},
        frequency: '{{ $popup->popup_frequency ?? "once_per_user" }}',
        intervalDays: {{ (int) ($popup->popup_interval_days ?? 30) }},
        storageKey: '{{ $storageKey }}'
    })"
    x-show="visible" x-transition.opacity x-cloak
    class="fixed inset-0 z-[999] flex items-center justify-center p-4"
    @keydown.escape.window="close()">
    
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="close()"></div>
    
    <div class="relative bg-white rounded-2xl shadow-2xl w-full overflow-hidden"
         style="max-width: {{ (int) ($popup->popup_max_width ?? 480) }}px" @click.outside="close()">
        <button @click="close()" aria-label="Close"
                class="absolute top-3 right-3 z-10 w-9 h-9 rounded-full bg-white/90 shadow flex items-center justify-center text-slate-600 hover:text-slate-900">
            &times;
        </button>

        @if($popup->type === 'image' && $popup->image_path)
            <img src="{{ \Illuminate\Support\Facades\Storage::url($popup->image_path) }}" alt="{{ $popup->name }}" class="w-full object-cover">
        @endif

        <div class="p-6">
            @if($popup->heading) 
                <h3 class="text-2xl font-display font-bold text-slate-900">{{ $popup->heading }}</h3>
            @endif
            
            @if($popup->subheading)
                <p class="mt-1 text-sm font-semibold text-brand-primary">{{ $popup->subheading }}</p>
            @endif
            
            @if($popup->description) 
                <div class="mt-3 text-sm text-slate-600 leading-relaxed">{!! nl2br(e($popup->description)) !!}</div>
            @endif
            
            @if($popup->type === 'html' && $popup->html_content)
                <div class="mt-3 prose prose-sm max-w-none">{!! $popup->html_content !!}</div>
            @endif
            
            @if($popup->cta_text)
                <a href="{{ $popup->cta_url ?? '#' }}" class="mt-5 w-full inline-flex items-center justify-center gap-2 px-6 py-3 bg-brand-primary text-white rounded-full font-bold hover:opacity-90 transition">
                    {{ $popup->cta_text }}
                </a>
            @endif
        </div>
    </div>
</div>

<script>
    function popupOverlay(cfg) {
        return {
            visible: false,
            alreadyShown() {
                const raw = localStorage.getItem(cfg.storageKey);
                if (!raw) return false;
                const rec = JSON.parse(raw);
                if (cfg.frequency === 'always') return false;
                if (cfg.frequency === 'once_per_session') return sessionStorage.getItem(cfg.storageKey) === '1';
                if (cfg.frequency === 'every_n_days') return (Date.now() - rec.ts) < (cfg.intervalDays * 86400000);
                return true; // once_per_user
            },
            markShown() {
                localStorage.setItem(cfg.storageKey, JSON.stringify({ts: Date.now()}));
                sessionStorage.setItem(cfg.storageKey, '1');
            },
            close() {
                this.visible = false;
                document.body.style.overflow = '';
            },
            init() {
                if (this.alreadyShown()) return;
                setTimeout(() => {
                    this.visible = true;
                    this.markShown();
                    document.body.style.overflow = 'hidden';
                }, cfg.delay * 1000);
            }
        }
    }
</script>
@endif