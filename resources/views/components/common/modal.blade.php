@props([
    'id' => 'modal',
    'title' => null,
    'maxWidth' => 'lg',
    'icon' => null,
    'headerBg' => 'brand'
])

@php
$maxWidthClass = match ($maxWidth) {
    'sm' => 'sm:max-w-sm',
    'md' => 'sm:max-w-md',
    'lg' => 'sm:max-w-lg',
    'xl' => 'sm:max-w-xl',
    '2xl' => 'sm:max-w-2xl',
    '3xl' => 'sm:max-w-3xl',
    '4xl' => 'sm:max-w-4xl',
    default => 'sm:max-w-lg',
};
@endphp

<div x-data="{
        show: false,
        modalData: {},
        open(data = {}) {
            this.modalData = data;
            this.show = true;
            document.body.classList.add('overflow-hidden');
        },
        close() {
            this.show = false;
            document.body.classList.remove('overflow-hidden');
        }
     }"
     x-init="
        $watch('show', value => {
            if (value) {
                document.body.classList.add('overflow-hidden');
            } else {
                document.body.classList.remove('overflow-hidden');
            }
        });
     "
     @open-modal.window="if ($event.detail === '{{ $id }}' || $event.detail?.name === '{{ $id }}' || $event.detail?.id === '{{ $id }}') open($event.detail?.data || {})"
     @close-modal.window="if ($event.detail === '{{ $id }}' || $event.detail?.name === '{{ $id }}' || $event.detail?.id === '{{ $id }}' || !$event.detail) close()"
     @keydown.escape.window="close()"
     x-show="show"
     class="fixed inset-0 z-[99999] overflow-y-auto"
     style="display: none;">

    <!-- Backdrop -->
    <div x-show="show"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-gray-900/60 dark:bg-gray-950/80 backdrop-blur-xs transition-opacity"
         @click="close()"></div>

    <!-- Modal Dialog -->
    <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
        <div x-show="show"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             @click.stop
             class="relative transform overflow-hidden rounded-2xl bg-white dark:bg-gray-800 text-left shadow-2xl transition-all w-full {{ $maxWidthClass }} border border-gray-200 dark:border-gray-700">

            <!-- Header -->
            @if ($title || isset($header) || $icon)
                <div class="flex items-center justify-between {{ $headerBg === 'brand' ? 'bg-brand-500 text-white' : 'border-b border-gray-100 dark:border-gray-700/60 text-gray-900 dark:text-white' }} px-6 py-4">
                    <div class="flex items-center gap-3">
                        @if ($icon)
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg {{ $headerBg === 'brand' ? 'bg-white/15 text-white' : 'bg-brand-50 dark:bg-brand-500/10 text-brand-600 dark:text-brand-400' }}">
                                <i class="{{ $icon }} text-base"></i>
                            </span>
                        @endif

                        @if (isset($header))
                            {{ $header }}
                        @else
                            <h3 class="text-lg font-bold {{ $headerBg === 'brand' ? 'text-white' : 'text-gray-900 dark:text-white' }}" x-text="modalData.title || @json($title)"></h3>
                        @endif
                    </div>

                    <button @click="close()"
                            type="button"
                            class="{{ $headerBg === 'brand' ? 'text-white/80 hover:text-white hover:bg-white/15' : 'text-gray-400 hover:text-gray-600 dark:hover:text-gray-200' }} p-1.5 rounded-lg transition-colors"
                            aria-label="Tutup Modal">
                        <i class="fa-solid fa-xmark text-lg {{ $headerBg === 'brand' ? 'text-white' : '' }}"></i>
                    </button>
                </div>
            @endif

            <!-- Body -->
            <div class="px-6 py-5 text-gray-700 dark:text-gray-300 text-sm">
                {{ $slot }}
            </div>

            <!-- Footer (Optional) -->
            @if (isset($footer))
                <div class="border-t border-gray-100 dark:border-gray-700/60 bg-gray-50 dark:bg-gray-800/50 px-6 py-3.5 flex flex-wrap items-center justify-end gap-2.5">
                    {{ $footer }}
                </div>
            @endif
        </div>
    </div>
</div>
