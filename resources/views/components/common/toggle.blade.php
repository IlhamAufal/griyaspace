@props([
    'name' => 'is_active',
    'id' => null,
    'label' => 'Status',
    'checked' => true,
    'activeLabel' => 'Aktif',
    'inactiveLabel' => 'Nonaktif',
    'valueOn' => '1',
    'valueOff' => '0'
])

@php
    $elementId = $id ?? $name;
    $isChecked = is_bool($checked) ? $checked : ($checked === $valueOn || $checked === 1 || $checked === '1' || $checked === true);
@endphp

<div x-data="{ enabled: {{ $isChecked ? 'true' : 'false' }} }" class="space-y-2">
    @if($label)
        <label for="{{ $elementId }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
            {{ $label }}
        </label>
    @endif

    <div class="flex items-center gap-3">
        <button type="button"
                id="{{ $elementId }}"
                @click="enabled = !enabled"
                class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900"
                :class="enabled ? 'bg-brand-500' : 'bg-gray-200 dark:bg-gray-700'"
                role="switch"
                :aria-checked="enabled">
            <span aria-hidden="true"
                  class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"
                  :class="enabled ? 'translate-x-5' : 'translate-x-0'"></span>
        </button>

        <input type="hidden" name="{{ $name }}" :value="enabled ? '{{ $valueOn }}' : '{{ $valueOff }}'">

        <span class="text-sm font-medium"
              :class="enabled ? 'text-green-600 dark:text-green-400' : 'text-gray-500 dark:text-gray-400'"
              x-text="enabled ? '{{ $activeLabel }}' : '{{ $inactiveLabel }}'"></span>
    </div>

    @error($name)
        <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
    @enderror
</div>
