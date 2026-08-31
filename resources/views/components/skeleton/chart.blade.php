@props([
    'height' => '300px',
    'type' => 'area', // area | donut | bar
])

<div {{ $attributes->merge(['class' => 'w-full animate-pulse flex flex-col justify-between p-4 rounded-xl bg-gray-50/50 dark:bg-gray-900/30']) }} style="min-height: {{ $height }};">
    @if($type === 'donut')
    <div class="flex items-center justify-center flex-1 my-auto">
        <div class="relative w-40 h-40 rounded-full border-8 border-gray-200 dark:border-gray-700 flex items-center justify-center">
            <div class="w-20 h-20 rounded-full bg-gray-100 dark:bg-gray-800"></div>
        </div>
    </div>
    @elseif($type === 'bar')
    <div class="grid grid-cols-7 gap-3 flex-1 px-4 pb-2 pt-6">
        @for($b = 0; $b < 7; $b++)
        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-t-md" style="height: {{ [40, 75, 55, 90, 65, 80, 45][$b] }}%;"></div>
        @endfor
    </div>
    @else
    <!-- Area / Line Wave Chart Skeleton -->
    <div class="grid grid-cols-8 gap-2 flex-1 px-2 pb-2 pt-8">
        @for($l = 0; $l < 8; $l++)
        <div class="flex flex-col items-center gap-1">
            <div class="w-2 h-2 rounded-full bg-gray-300 dark:bg-gray-600"></div>
            <div class="w-full bg-gradient-to-t from-gray-200 to-gray-100 dark:from-gray-700 dark:to-gray-800 rounded-t-md" style="height: {{ [30, 60, 45, 85, 50, 70, 95, 60][$l] }}%;"></div>
        </div>
        @endfor
    </div>
    @endif

    <!-- Bottom X-axis labels skeleton -->
    <div class="grid {{ $type === 'donut' ? 'grid-cols-4' : 'grid-cols-6' }} gap-2 pt-3 border-t border-gray-100 dark:border-gray-700/50">
        @for($x = 0; $x < ($type === 'donut' ? 4 : 6); $x++)
        <div class="h-2.5 bg-gray-200 dark:bg-gray-700 rounded w-full"></div>
        @endfor
    </div>
</div>
