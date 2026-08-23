@props([
    'title',
    'value',
    'icon',
    'color' => 'navy', // navy | kuning | toska | brand | secondary | accent
    'subtitle' => null,
    'badge' => null,
])

@php
    $theme = match($color) {
        'navy', 'brand', 'primary', 'blue', 'purple' => [
            'card' => 'bg-brand-50/40 dark:bg-brand-950/20 border-brand-100 dark:border-brand-900/50 hover:border-brand-300 dark:hover:border-brand-700',
            'icon' => 'bg-[#2F3185] text-white shadow-brand-500/25',
            'value' => 'text-[#2F3185] dark:text-brand-300',
            'badge' => 'bg-brand-100 text-[#2F3185] dark:bg-brand-900/50 dark:text-brand-300',
            'accent' => 'from-[#2F3185] to-brand-400',
        ],
        'kuning', 'secondary', 'yellow', 'orange' => [
            'card' => 'bg-amber-50/40 dark:bg-amber-950/20 border-amber-100 dark:border-amber-900/40 hover:border-amber-300 dark:hover:border-amber-700',
            'icon' => 'bg-[#FFB800] text-gray-900 shadow-amber-500/25',
            'value' => 'text-amber-900 dark:text-amber-300',
            'badge' => 'bg-amber-100 text-amber-900 dark:bg-amber-900/50 dark:text-amber-300',
            'accent' => 'from-[#FFB800] to-amber-400',
        ],
        'toska', 'accent', 'third', 'green', 'teal' => [
            'card' => 'bg-teal-50/40 dark:bg-teal-950/20 border-teal-100 dark:border-teal-900/40 hover:border-teal-300 dark:hover:border-teal-700',
            'icon' => 'bg-[#1CBDB3] text-white shadow-teal-500/25',
            'value' => 'text-teal-900 dark:text-teal-300',
            'badge' => 'bg-teal-100 text-teal-900 dark:bg-teal-900/50 dark:text-teal-300',
            'accent' => 'from-[#1CBDB3] to-teal-400',
        ],
        default => [
            'card' => 'bg-gray-50 dark:bg-gray-800/60 border-gray-200 dark:border-gray-700 hover:border-gray-300',
            'icon' => 'bg-gray-700 text-white shadow-gray-500/20',
            'value' => 'text-gray-900 dark:text-white',
            'badge' => 'bg-gray-100 text-gray-700',
            'accent' => 'from-gray-400 to-gray-600',
        ],
    };
@endphp

<div {{ $attributes->merge(['class' => 'group relative overflow-hidden rounded-2xl border p-5 shadow-xs hover:shadow-md transition-all duration-200 ' . $theme['card']]) }}>
    <!-- Subtle Top Gradient Border -->
    <div class="absolute top-0 inset-x-0 h-1 bg-gradient-to-r {{ $theme['accent'] }} opacity-90"></div>

    <div class="flex items-start justify-between gap-3">
        <div class="space-y-1 min-w-0 flex-1">
            <p class="text-xs sm:text-sm font-medium text-gray-600 dark:text-gray-400 truncate">{{ $title }}</p>
            <p class="text-2xl sm:text-3xl font-extrabold tracking-tight {{ $theme['value'] }}">{{ $value }}</p>
            @if($subtitle)
                <p class="text-xs text-gray-500 dark:text-gray-400 flex items-center gap-1.5 pt-1">
                    @if($badge)
                        <span class="px-1.5 py-0.5 rounded text-[10px] font-bold uppercase {{ $theme['badge'] }}">{{ $badge }}</span>
                    @endif
                    <span class="truncate">{{ $subtitle }}</span>
                </p>
            @endif
        </div>
        <div class="w-12 h-12 rounded-xl {{ $theme['icon'] }} flex items-center justify-center shrink-0 shadow-md group-hover:scale-110 transition-transform">
            <i class="fa-solid {{ $icon }} text-lg"></i>
        </div>
    </div>
</div>
