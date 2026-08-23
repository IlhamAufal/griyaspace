@props([
    'title',
    'icon' => null,
    'count' => null,
    'dotColor' => 'brand',
    'href' => null,
])

@php
    $dotColorClass = match($dotColor) {
        'green' => 'bg-green-500',
        'yellow' => 'bg-yellow-500',
        'blue' => 'bg-blue-500',
        'red' => 'bg-red-500',
        'orange' => 'bg-orange-500',
        default => 'bg-brand-500',
    };
@endphp

<div>
    <div class="flex items-center justify-between mb-3">
        <h2 class="text-base font-bold text-gray-900 dark:text-white flex items-center gap-2">
            <span class="w-2 h-2 rounded-full {{ $dotColorClass }}"></span>
            {{ $title }}
        </h2>
        <div class="flex items-center gap-3">
            @if($count !== null)
                <span class="text-xs text-gray-400 dark:text-gray-500">{{ $count }} data</span>
            @endif
            @if($href)
                <a href="{{ $href }}" class="text-xs font-medium text-brand-500 hover:text-brand-600 transition-colors">Lihat Semua <i class="fa-solid fa-arrow-right text-[10px] ml-0.5"></i></a>
            @endif
        </div>
    </div>
    <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800 overflow-hidden">
        {{ $slot }}
    </div>
</div>
