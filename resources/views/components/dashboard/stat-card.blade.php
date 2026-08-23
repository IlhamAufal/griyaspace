@props([
    'title',
    'value',
    'icon',
    'color' => 'brand',
    'subtitle' => null,
])

@php
    $colorClasses = match($color) {
        'yellow' => 'bg-yellow-50 text-yellow-500 dark:bg-yellow-500/10',
        'green' => 'bg-green-50 text-green-500 dark:bg-green-500/10',
        'blue' => 'bg-blue-50 text-blue-500 dark:bg-blue-500/10',
        'purple' => 'bg-purple-50 text-purple-500 dark:bg-purple-500/10',
        'orange' => 'bg-orange-50 text-orange-500 dark:bg-orange-500/10',
        'red' => 'bg-red-50 text-red-500 dark:bg-red-500/10',
        default => 'bg-brand-50 text-brand-500 dark:bg-brand-500/10',
    };

    $valueColorClasses = match($color) {
        'yellow' => 'text-yellow-500',
        'green' => 'text-green-500',
        'blue' => 'text-blue-500',
        'purple' => 'text-purple-500',
        'orange' => 'text-orange-500',
        'red' => 'text-red-500',
        default => 'text-brand-500',
    };
@endphp

<div {{ $attributes->merge(['class' => 'group rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800 hover:shadow-md transition-shadow']) }}>
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ $title }}</p>
            <p class="mt-2 text-3xl font-bold {{ $valueColorClasses }}">{{ $value }}</p>
            @if($subtitle)
                <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">{{ $subtitle }}</p>
            @endif
        </div>
        <div class="w-12 h-12 rounded-xl {{ $colorClasses }} flex items-center justify-center group-hover:scale-110 transition-transform">
            <i class="fa-solid {{ $icon }} text-xl"></i>
        </div>
    </div>
</div>
