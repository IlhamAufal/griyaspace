<div {{ $attributes->merge(['class' => 'w-full animate-pulse space-y-4']) }}>
    <!-- Calendar Toolbar Skeleton -->
    <div class="flex flex-wrap items-center justify-between gap-4 p-4 rounded-xl bg-gray-50 dark:bg-gray-800/60 border border-gray-200 dark:border-gray-700/60">
        <div class="flex items-center gap-2">
            <div class="h-9 w-9 bg-gray-200 dark:bg-gray-700 rounded-lg"></div>
            <div class="h-9 w-20 bg-gray-200 dark:bg-gray-700 rounded-lg"></div>
            <div class="h-9 w-9 bg-gray-200 dark:bg-gray-700 rounded-lg"></div>
        </div>
        <div class="h-6 bg-gray-200 dark:bg-gray-700 rounded w-48"></div>
        <div class="flex items-center gap-3">
            <div class="h-9 w-36 bg-gray-200 dark:bg-gray-700 rounded-lg"></div>
            <div class="h-9 w-32 bg-gray-200 dark:bg-gray-700 rounded-lg"></div>
        </div>
    </div>

    <!-- Calendar Grid Skeleton -->
    <div class="rounded-2xl border border-gray-200 dark:border-gray-700/60 bg-white dark:bg-gray-800 p-4">
        <!-- Day Header -->
        <div class="grid grid-cols-7 gap-2 pb-3 mb-3 border-b border-gray-100 dark:border-gray-700/60 text-center">
            @for($d = 0; $d < 7; $d++)
            <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded mx-auto w-12"></div>
            @endfor
        </div>

        <!-- Time Grid Rows -->
        <div class="space-y-3">
            @for($row = 0; $row < 6; $row++)
            <div class="grid grid-cols-7 gap-2 h-16">
                @for($col = 0; $col < 7; $col++)
                <div class="rounded-lg border border-gray-100 dark:border-gray-700/40 bg-gray-50/50 dark:bg-gray-900/30 p-1.5 flex flex-col justify-between">
                    <div class="h-2 w-4 bg-gray-200 dark:bg-gray-700 rounded"></div>
                    @if(($row + $col) % 3 === 0)
                    <div class="h-6 bg-brand-100 dark:bg-brand-900/40 rounded p-1">
                        <div class="h-2 bg-brand-300 dark:bg-brand-700 rounded w-3/4"></div>
                    </div>
                    @endif
                </div>
                @endfor
            </div>
            @endfor
        </div>
    </div>
</div>
