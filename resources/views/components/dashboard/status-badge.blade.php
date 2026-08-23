@props([
    'status',
    'label' => null,
])

@php
    $enum = $status instanceof \App\Enums\BookingStatus
        ? $status
        : \App\Enums\BookingStatus::tryFrom((string) $status);

    $displayLabel = $label ?? ($enum?->label() ?? ucfirst((string) $status));

    $colorClasses = $enum?->colorClasses() ?? 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300';
@endphp

<span {{ $attributes->merge(['class' => 'inline-block rounded-full px-2.5 py-0.5 text-xs font-semibold ' . $colorClasses]) }}>
    {{ $displayLabel }}
</span>
