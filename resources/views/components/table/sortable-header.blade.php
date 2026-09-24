@props([
    'column',
    'label',
])

@php
    $currentSort = request('sort', '');
    $currentDirection = request('direction', 'asc');
    $isActive = $currentSort === $column;

    if ($isActive) {
        $newDirection = $currentDirection === 'asc' ? 'desc' : 'asc';
    } else {
        $newDirection = 'asc';
    }
@endphp

<th {{ $attributes->merge(['class' => 'px-6 py-3.5 text-left text-xs font-bold text-white uppercase tracking-wider']) }}>
    <span @click.prevent="$dispatch('sort', { column: '{{ $column }}', direction: '{{ $newDirection }}' })"
          class="group inline-flex items-center gap-1.5 select-none cursor-pointer transition-colors {{ $isActive ? 'text-white' : 'text-white/90 hover:text-white' }}">
        <span>{{ $label }}</span>
        <span class="flex flex-col -space-y-1">
            <i class="fa-solid fa-caret-up text-[10px] leading-none {{ $isActive && $newDirection === 'desc' ? 'text-white/40' : 'text-white' }}"></i>
            <i class="fa-solid fa-caret-down text-[10px] leading-none {{ $isActive && $newDirection === 'asc' ? 'text-white/40' : 'text-white' }}"></i>
        </span>
    </span>
</th>
