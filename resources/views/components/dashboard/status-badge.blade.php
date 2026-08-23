@props([
    'status',
    'label' => null,
])

@php
    $displayLabel = $label ?? ucfirst($status);

    $colorClasses = match($status) {
        'submitted', 'pending' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400',
        'approved' => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
        'rejected' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
        'revision' => 'bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400',
        'cancelled' => 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300',
        default => 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300',
    };
@endphp

<span {{ $attributes->merge(['class' => 'inline-block rounded-full px-2.5 py-0.5 text-xs font-semibold ' . $colorClasses]) }}>
    {{ $displayLabel }}
</span>
