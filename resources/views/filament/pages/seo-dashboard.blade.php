<x-filament-panels::page>
    <!-- Stats are rendered via getHeaderWidgets() automatically -->

    <x-filament::section class="mt-6">
        <x-slot name="heading">
            Issues & Recommendations
            <span class="bg-gray-200 text-gray-700 dark:bg-gray-700 dark:text-gray-200 px-2 py-1 rounded text-xs font-bold ml-2">{{ count($issues) }} Found</span>
        </x-slot>

        @if(count($issues) > 0)
            <div class="divide-y divide-gray-100 dark:divide-white/5">
                @foreach($issues as $issue)
                    <div class="py-4 flex items-start gap-4">
                        <div class="flex-shrink-0 mt-1">
                            @if($issue['type'] === 'critical')
                                <x-heroicon-m-x-circle class="w-6 h-6 text-danger-500" />
                            @else
                                <x-heroicon-m-exclamation-triangle class="w-6 h-6 text-warning-500" />
                            @endif
                        </div>
                        <div>
                            <p class="font-medium text-gray-950 dark:text-white">{{ $issue['message'] }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                @if($issue['type'] === 'critical')
                                    Immediate action required. This severely affects your ranking.
                                @else
                                    Recommended fix to improve click-through rates.
                                @endif
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="p-12 text-center text-gray-400 dark:text-gray-500">
                <x-heroicon-o-check-circle class="w-16 h-16 mx-auto text-success-500 mb-4" />
                <p class="text-lg font-medium text-gray-600 dark:text-gray-300">Great job!</p>
                <p>No critical SEO issues found on your website.</p>
            </div>
        @endif
    </x-filament::section>
</x-filament-panels::page>
