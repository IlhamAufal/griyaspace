@props([
    'title',
    'icon' => null,
    'count' => null,
    'subtitle' => null,
    'href' => null,
])


<div>
    <div class="flex items-center justify-between mb-3">
        <div>
            <h2 class="text-base font-bold text-gray-900 dark:text-white flex items-center gap-2">
                {{ $title }}
            </h2>
            @if($subtitle)
                <p class="text-[11px] text-gray-400 dark:text-gray-500 mt-0.5">{{ $subtitle }}</p>
            @endif
        </div>
    </div>
    <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800 overflow-hidden">
        {{ $slot }}
    </div>
</div>
