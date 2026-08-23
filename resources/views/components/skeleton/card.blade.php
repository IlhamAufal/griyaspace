@props([
    'count' => 1,
])

@for($i = 0; $i < $count; $i++)
<div {{ $attributes->merge(['class' => 'rounded-2xl border border-gray-200 dark:border-gray-700/60 bg-white dark:bg-gray-800 p-5 shadow-xs animate-pulse']) }}>
    <div class="flex items-start justify-between gap-3">
        <div class="space-y-2 flex-1">
            <div class="h-3.5 bg-gray-200 dark:bg-gray-700 rounded w-1/2"></div>
            <div class="h-7 bg-gray-200 dark:bg-gray-700 rounded w-1/3"></div>
            <div class="h-2.5 bg-gray-100 dark:bg-gray-800 rounded w-2/3 mt-1"></div>
        </div>
        <div class="w-12 h-12 rounded-xl bg-gray-200 dark:bg-gray-700 shrink-0"></div>
    </div>
</div>
@endfor
