@props([
    'count' => 1,
])

@for($i = 0; $i < $count; $i++)
<div {{ $attributes->merge(['class' => 'flex flex-col bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700/60 shadow-sm overflow-hidden animate-pulse']) }}>
    <!-- Photo Header Skeleton -->
    <div class="h-48 w-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
        <i class="fa-regular fa-image text-3xl text-gray-300 dark:text-gray-600"></i>
    </div>

    <!-- Card Body Skeleton -->
    <div class="p-5 flex-1 flex flex-col justify-between space-y-4">
        <div class="space-y-3">
            <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-3/4"></div>
            
            <div class="flex items-center gap-2.5 pt-1">
                <div class="w-7 h-7 rounded-lg bg-gray-200 dark:bg-gray-700 shrink-0"></div>
                <div class="space-y-1 flex-1">
                    <div class="h-2.5 bg-gray-100 dark:bg-gray-800 rounded w-1/3"></div>
                    <div class="h-3.5 bg-gray-200 dark:bg-gray-700 rounded w-2/3"></div>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-2 pt-1">
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 rounded-lg bg-gray-200 dark:bg-gray-700 shrink-0"></div>
                    <div class="space-y-1 flex-1">
                        <div class="h-2.5 bg-gray-100 dark:bg-gray-800 rounded w-1/2"></div>
                        <div class="h-3 bg-gray-200 dark:bg-gray-700 rounded w-3/4"></div>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 rounded-lg bg-gray-200 dark:bg-gray-700 shrink-0"></div>
                    <div class="space-y-1 flex-1">
                        <div class="h-2.5 bg-gray-100 dark:bg-gray-800 rounded w-1/2"></div>
                        <div class="h-3 bg-gray-200 dark:bg-gray-700 rounded w-3/4"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="pt-4 border-t border-gray-100 dark:border-gray-700/60 flex items-center justify-end gap-2">
            <div class="h-7 w-16 bg-gray-200 dark:bg-gray-700 rounded-lg"></div>
            <div class="h-7 w-16 bg-gray-200 dark:bg-gray-700 rounded-lg"></div>
        </div>
    </div>
</div>
@endfor
