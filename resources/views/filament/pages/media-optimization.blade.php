<x-filament-panels::page>

    <style>
        /* === Media Optimization Page — scoped styles === */
        .mo-stats { display: grid; grid-template-columns: repeat(2,1fr); gap: 1rem; }
        @media(min-width:768px) { .mo-stats { grid-template-columns: repeat(4,1fr); } }
        .mo-stat { text-align: center; padding: 1rem; border-radius: 0.75rem; border: 1px solid rgba(255,255,255,0.08); background: rgba(255,255,255,0.04); }
        .mo-stat-val { font-size: 1.75rem; font-weight: 900; line-height: 1.2; }
        .mo-stat-lbl { font-size: 0.7rem; opacity: 0.45; margin-top: 0.25rem; }

        .mo-score-hd { display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 0.75rem; margin-bottom: 0.75rem; }
        .mo-score-acts { display: flex; align-items: center; gap: 0.75rem; }
        .mo-score-pct { font-size: 1.5rem; font-weight: 900; }
        .mo-pbar { width: 100%; border-radius: 9999px; overflow: hidden; background: rgba(128,128,128,0.2); }
        .mo-pbar-fill { height: 100%; border-radius: 9999px; transition: width 0.5s ease; }
        .mo-saved { font-size: 0.75rem; opacity: 0.4; margin-top: 0.5rem; }

        .mo-cats { display: grid; grid-template-columns: 1fr; gap: 1rem; }
        @media(min-width:768px) { .mo-cats { grid-template-columns: repeat(2,1fr); } }
        @media(min-width:1280px) { .mo-cats { grid-template-columns: repeat(3,1fr); } }
        .mo-cat { padding: 1.25rem; border-radius: 0.75rem; border: 1px solid rgba(128,128,128,0.2); transition: border-color 0.2s; }
        .mo-cat:hover { border-color: #eab308; }
        .mo-cat-hd { display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1rem; }
        .mo-cat-ic { padding: 0.5rem; border-radius: 0.5rem; background: rgba(234,179,8,0.1); }
        .mo-cat-title { font-weight: 600; font-size: 0.9rem; }
        .mo-cat-sub { font-size: 0.75rem; opacity: 0.5; }
        .mo-cat-nums { display: grid; grid-template-columns: repeat(2,1fr); gap: 0.75rem; margin-bottom: 1rem; }
        .mo-mini { text-align: center; padding: 0.5rem; border-radius: 0.5rem; }
        .mo-mini-v { font-size: 1.125rem; font-weight: 700; line-height: 1; }
        .mo-mini-l { font-size: 0.6rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; margin-top: 0.125rem; }
        .mo-mini-green { background: rgba(16,185,129,0.1); }
        .mo-mini-yellow { background: rgba(245,158,11,0.1); }
        .mo-mini-gray { background: rgba(128,128,128,0.08); }
        .mo-cat-size { font-size: 0.75rem; opacity: 0.4; margin-bottom: 0.75rem; }
        .mo-cat-ok { display: flex; align-items: center; justify-content: center; gap: 0.5rem; padding: 0.375rem; font-size: 0.875rem; }

        .mo-issues-list > div + div { border-top: 1px solid rgba(128,128,128,0.15); }
        .mo-issue { display: flex; align-items: flex-start; gap: 1rem; padding: 1rem 0; }
        .mo-issue-ic { flex-shrink: 0; padding: 0.375rem; border-radius: 9999px; }
        .mo-issue-body { flex: 1; min-width: 0; }
        .mo-badges { display: flex; gap: 0.5rem; margin-bottom: 0.375rem; }
        .mo-badge { display: inline-flex; padding: 0.125rem 0.5rem; border-radius: 0.25rem; font-size: 0.6rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; }
        .mo-badge-perf { background: rgba(59,130,246,0.15); color: #60a5fa; }
        .mo-badge-seo { background: rgba(139,92,246,0.15); color: #a78bfa; }
        .mo-badge-quality { background: rgba(249,115,22,0.15); color: #fb923c; }
        .mo-badge-crit { background: rgba(239,68,68,0.15); color: #f87171; }
        .mo-badge-warn { background: rgba(245,158,11,0.15); color: #fbbf24; }
        .mo-issue-title { font-weight: 500; font-size: 0.9rem; }
        .mo-issue-detail { font-size: 0.8rem; opacity: 0.5; margin-top: 0.25rem; }
        .mo-all-clear { padding: 3rem; text-align: center; }
        .mo-all-clear p:first-of-type { font-size: 1.1rem; font-weight: 600; opacity: 0.8; }
        .mo-all-clear p:last-of-type { font-size: 0.85rem; opacity: 0.4; margin-top: 0.25rem; }

        .mo-sys { display: grid; grid-template-columns: 1fr; gap: 1.5rem; }
        @media(min-width:768px) { .mo-sys { grid-template-columns: repeat(3,1fr); } }
        .mo-sys h4 { font-weight: 600; font-size: 0.9rem; margin-bottom: 0.75rem; }
        .mo-dl { display: flex; flex-direction: column; gap: 0.5rem; font-size: 0.85rem; }
        .mo-dl-row { display: flex; justify-content: space-between; }
        .mo-dl-row dt { opacity: 0.5; }
        .mo-dl-row dd { font-weight: 500; }
        .mo-cmds { display: grid; grid-template-columns: 1fr; gap: 0.5rem; margin-top: 1rem; padding: 0.75rem; border-radius: 0.5rem; background: rgba(128,128,128,0.08); }
        @media(min-width:768px) { .mo-cmds { grid-template-columns: repeat(2,1fr); } }
        .mo-cmds code { font-family: ui-monospace, monospace; font-size: 0.75rem; opacity: 0.6; }
        .mo-cmds-title { font-weight: 600; font-size: 0.85rem; margin-bottom: 0.5rem; }

        .mo-rescan { display: flex; justify-content: center; margin-top: 1.5rem; }

        /* Color helpers */
        .mo-green { color: #10b981; }
        .mo-yellow { color: #f59e0b; }
        .mo-red { color: #ef4444; }
        .mo-ic-red-bg { background: rgba(239,68,68,0.1); }
        .mo-ic-yellow-bg { background: rgba(245,158,11,0.1); }
        .mo-section-hd { font-size: 1rem; font-weight: 700; display: flex; align-items: center; gap: 0.5rem; }
        .mo-section-sub { font-size: 0.75rem; opacity: 0.5; margin-top: 0.25rem; }
        .mo-count-badge { display: inline-flex; padding: 0.125rem 0.5rem; border-radius: 9999px; font-size: 0.7rem; font-weight: 700; background: rgba(239,68,68,0.15); color: #f87171; }
    </style>

    @php
        $totalImages = collect($scanResults)->sum('total');
        $totalOptimized = collect($scanResults)->sum('optimized');
        $totalUnoptimized = collect($scanResults)->sum('unoptimized');
        $totalSize = collect($scanResults)->sum('total_size');
        $optimizedSize = collect($scanResults)->sum('optimized_size');
        $pctOptimized = $totalImages > 0 ? round($totalOptimized / $totalImages * 100) : 0;
        $webpEnabled = function_exists('imagewebp');
    @endphp

    {{-- Top Stats --}}
    <x-filament::section>
        <div class="mo-stats">
            <div class="mo-stat">
                <div class="mo-stat-val">{{ $totalImages }}</div>
                <div class="mo-stat-lbl">Total Images</div>
            </div>
            <div class="mo-stat">
                <div class="mo-stat-val mo-green">{{ $totalOptimized }}</div>
                <div class="mo-stat-lbl">Optimized</div>
            </div>
            <div class="mo-stat">
                <div class="mo-stat-val {{ $totalUnoptimized > 0 ? 'mo-yellow' : 'mo-green' }}">{{ $totalUnoptimized }}</div>
                <div class="mo-stat-lbl">Need Optimization</div>
            </div>
            <div class="mo-stat">
                <div class="mo-stat-val {{ $webpEnabled ? 'mo-green' : 'mo-red' }}">{{ $webpEnabled ? 'ON' : 'OFF' }}</div>
                <div class="mo-stat-lbl">WebP Format</div>
            </div>
        </div>
    </x-filament::section>

    {{-- Optimization Score --}}
    <x-filament::section>
        <div class="mo-score-hd">
            <div>
                <div class="mo-section-hd">Optimization Score</div>
                <div class="mo-section-sub">{{ $totalOptimized }} of {{ $totalImages }} images optimized</div>
            </div>
            <div class="mo-score-acts">
                <span class="mo-score-pct {{ $pctOptimized >= 80 ? 'mo-green' : ($pctOptimized >= 50 ? 'mo-yellow' : 'mo-red') }}">{{ $pctOptimized }}%</span>
                <x-filament::button wire:click="optimizeAll" wire:loading.attr="disabled" color="warning" icon="heroicon-m-bolt" size="sm">
                    <span wire:loading.remove wire:target="optimizeAll">Optimize All</span>
                    <span wire:loading wire:target="optimizeAll">Optimizing...</span>
                </x-filament::button>
            </div>
        </div>
        <div class="mo-pbar" style="height: 0.75rem;">
            <div class="mo-pbar-fill {{ $pctOptimized >= 80 ? 'mo-green' : ($pctOptimized >= 50 ? 'mo-yellow' : 'mo-red') }}"
                 style="width: {{ $pctOptimized }}%; background: {{ $pctOptimized >= 80 ? '#10b981' : ($pctOptimized >= 50 ? '#f59e0b' : '#ef4444') }};"></div>
        </div>
        @if($totalSize > 0)
            <div class="mo-saved">
                Total: {{ \App\Filament\Pages\MediaOptimization::formatBytes($totalSize) }}
                @if($optimizedSize > 0) &rarr; {{ \App\Filament\Pages\MediaOptimization::formatBytes($optimizedSize) }} (saved {{ round((1 - $optimizedSize / $totalSize) * 100) }}%) @endif
            </div>
        @endif
    </x-filament::section>

    {{-- Image Scanner --}}
    <x-filament::section>
        <x-slot name="heading">
            <span class="mo-section-hd">
                <x-heroicon-o-camera style="width:1.25rem;height:1.25rem;" /> Image Scanner
            </span>
        </x-slot>
        <x-slot name="description">Scan and optimize images across all content types</x-slot>

        <div class="mo-cats">
            @foreach($scanResults as $key => $result)
                <div class="mo-cat">
                    <div class="mo-cat-hd">
                        <div class="mo-cat-ic">
                            <x-dynamic-component :component="$result['icon']" style="width:1.25rem;height:1.25rem;color:#eab308;" />
                        </div>
                        <div>
                            <div class="mo-cat-title">{{ $result['label'] }}</div>
                            <div class="mo-cat-sub">{{ $result['total'] }} image(s)</div>
                        </div>
                    </div>

                    <div class="mo-cat-nums">
                        <div class="mo-mini mo-mini-green">
                            <div class="mo-mini-v mo-green">{{ $result['optimized'] }}</div>
                            <div class="mo-mini-l mo-green">Optimized</div>
                        </div>
                        <div class="mo-mini {{ $result['unoptimized'] > 0 ? 'mo-mini-yellow' : 'mo-mini-gray' }}">
                            <div class="mo-mini-v {{ $result['unoptimized'] > 0 ? 'mo-yellow' : '' }}" style="{{ $result['unoptimized'] == 0 ? 'opacity:0.4' : '' }}">{{ $result['unoptimized'] }}</div>
                            <div class="mo-mini-l {{ $result['unoptimized'] > 0 ? 'mo-yellow' : '' }}" style="{{ $result['unoptimized'] == 0 ? 'opacity:0.4' : '' }}">Pending</div>
                        </div>
                    </div>

                    @if($result['total_size'] > 0)
                        <div class="mo-cat-size">
                            Size: {{ \App\Filament\Pages\MediaOptimization::formatBytes($result['total_size']) }}
                            @if($result['optimized_size'] > 0) &rarr; {{ \App\Filament\Pages\MediaOptimization::formatBytes($result['optimized_size']) }} @endif
                        </div>
                    @endif

                    @php $pct = $result['total'] > 0 ? round($result['optimized'] / $result['total'] * 100) : 100; @endphp
                    <div class="mo-pbar" style="height: 0.375rem; margin-bottom: 1rem;">
                        <div class="mo-pbar-fill" style="width: {{ $pct }}%; background: {{ $pct >= 100 ? '#10b981' : ($pct >= 50 ? '#f59e0b' : '#ef4444') }};"></div>
                    </div>

                    @if($result['unoptimized'] > 0)
                        <x-filament::button
                            wire:click="{{ $result['command'] }}"
                            wire:loading.attr="disabled"
                            color="warning"
                            size="sm"
                            icon="heroicon-m-bolt"
                            style="width:100%;"
                        >
                            <span wire:loading.remove wire:target="{{ $result['command'] }}">Optimize {{ $result['unoptimized'] }} Image(s)</span>
                            <span wire:loading wire:target="{{ $result['command'] }}">Processing...</span>
                        </x-filament::button>
                    @else
                        <div class="mo-cat-ok mo-green">
                            <x-heroicon-m-check-circle style="width:1rem;height:1rem;" /> All optimized
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </x-filament::section>

    {{-- Content & SEO Issues --}}
    <x-filament::section>
        <x-slot name="heading">
            <span class="mo-section-hd">
                <x-heroicon-o-magnifying-glass style="width:1.25rem;height:1.25rem;" />
                Content &amp; SEO Issues
                @if(count($contentIssues) > 0)
                    <span class="mo-count-badge">{{ count($contentIssues) }}</span>
                @endif
            </span>
        </x-slot>
        <x-slot name="description">Detected issues that may affect performance and search rankings</x-slot>

        @if(count($contentIssues) > 0)
            <div class="mo-issues-list">
                @foreach($contentIssues as $issue)
                    <div class="mo-issue">
                        <div class="mo-issue-ic {{ $issue['type'] === 'critical' ? 'mo-ic-red-bg' : 'mo-ic-yellow-bg' }}">
                            @if($issue['type'] === 'critical')
                                <x-heroicon-m-x-circle style="width:1.25rem;height:1.25rem;color:#ef4444;" />
                            @else
                                <x-heroicon-m-exclamation-triangle style="width:1.25rem;height:1.25rem;color:#f59e0b;" />
                            @endif
                        </div>
                        <div class="mo-issue-body">
                            <div class="mo-badges">
                                <span class="mo-badge {{ $issue['category'] === 'Performance' ? 'mo-badge-perf' : ($issue['category'] === 'SEO' ? 'mo-badge-seo' : 'mo-badge-quality') }}">{{ $issue['category'] }}</span>
                                <span class="mo-badge {{ $issue['type'] === 'critical' ? 'mo-badge-crit' : 'mo-badge-warn' }}">{{ $issue['type'] }}</span>
                            </div>
                            <div class="mo-issue-title">{{ $issue['message'] }}</div>
                            <div class="mo-issue-detail">{{ $issue['detail'] }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="mo-all-clear">
                <x-heroicon-o-check-circle style="width:4rem;height:4rem;margin:0 auto 1rem;color:#10b981;" />
                <p>All Clear!</p>
                <p>No content or SEO issues detected.</p>
            </div>
        @endif
    </x-filament::section>

    {{-- System Info --}}
    <x-filament::section collapsible collapsed>
        <x-slot name="heading">
            <span class="mo-section-hd">
                <x-heroicon-o-cpu-chip style="width:1.25rem;height:1.25rem;" /> System Information
            </span>
        </x-slot>

        @php $gdInfo = function_exists('gd_info') ? gd_info() : []; @endphp

        <div class="mo-sys">
            <div>
                <h4>Image Engine</h4>
                <dl class="mo-dl">
                    <div class="mo-dl-row"><dt>Library</dt><dd>Intervention Image v3 (GD)</dd></div>
                    <div class="mo-dl-row"><dt>GD Version</dt><dd>{{ $gdInfo['GD Version'] ?? 'N/A' }}</dd></div>
                    <div class="mo-dl-row"><dt>WebP Support</dt><dd style="color:{{ $webpEnabled ? '#10b981' : '#ef4444' }}">{{ $webpEnabled ? '✓ Enabled' : '✗ Disabled' }}</dd></div>
                    <div class="mo-dl-row"><dt>JPEG Support</dt><dd style="color:#10b981;">✓ Enabled</dd></div>
                    <div class="mo-dl-row"><dt>PNG Support</dt><dd style="color:#10b981;">✓ Enabled</dd></div>
                </dl>
            </div>
            <div>
                <h4>Optimization Settings</h4>
                <dl class="mo-dl">
                    <div class="mo-dl-row"><dt>Max Width</dt><dd>{{ \App\Services\ImageOptimizationService::MAX_WIDTH }}px</dd></div>
                    <div class="mo-dl-row"><dt>Max Height</dt><dd>{{ \App\Services\ImageOptimizationService::MAX_HEIGHT }}px</dd></div>
                    <div class="mo-dl-row"><dt>Quality</dt><dd>{{ \App\Services\ImageOptimizationService::OPTIMIZED_QUALITY }}%</dd></div>
                    <div class="mo-dl-row"><dt>Thumb Size</dt><dd>{{ \App\Services\ImageOptimizationService::THUMB_WIDTH }}x{{ \App\Services\ImageOptimizationService::THUMB_HEIGHT }}px</dd></div>
                    <div class="mo-dl-row"><dt>Thumb Quality</dt><dd>{{ \App\Services\ImageOptimizationService::THUMB_QUALITY }}%</dd></div>
                </dl>
            </div>
            <div>
                <h4>Auto-Optimization</h4>
                <dl class="mo-dl">
                    <div class="mo-dl-row"><dt>Media Upload</dt><dd style="color:#10b981">✓ Active</dd></div>
                    <div class="mo-dl-row"><dt>Gallery Upload</dt><dd style="color:#10b981">✓ Active</dd></div>
                    <div class="mo-dl-row"><dt>Package Upload</dt><dd style="color:#10b981">✓ Active</dd></div>
                    <div class="mo-dl-row"><dt>Blog Upload</dt><dd style="color:#10b981">✓ Active</dd></div>
                    <div class="mo-dl-row"><dt>Destination Upload</dt><dd style="color:#10b981">✓ Active</dd></div>
                    <div class="mo-dl-row"><dt>Banner Upload</dt><dd style="color:#10b981">✓ Active</dd></div>
                </dl>
            </div>
        </div>

        <div class="mo-cmds">
            <div class="mo-cmds-title" style="grid-column: 1 / -1;">Available Commands</div>
            <code>php artisan media:optimize --all</code>
            <code>php artisan gallery:optimize</code>
            <code>php artisan packages:optimize-images</code>
            <code>php artisan heroes:optimize</code>
        </div>
    </x-filament::section>

    {{-- Rescan --}}
    <div class="mo-rescan">
        <x-filament::button wire:click="scanAll" wire:loading.attr="disabled" color="gray" icon="heroicon-m-arrow-path" size="sm">
            <span wire:loading.remove wire:target="scanAll">Rescan All Images</span>
            <span wire:loading wire:target="scanAll">Scanning...</span>
        </x-filament::button>
    </div>

</x-filament-panels::page>
