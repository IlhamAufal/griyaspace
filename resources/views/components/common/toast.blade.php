@props([])

<div x-data="toastManager()"
     @toast.window="addToast($event.detail)"
     class="fixed top-5 right-5 z-[999999] flex flex-col gap-3 max-w-sm w-full pointer-events-none px-4 sm:px-0"
     role="region"
     aria-live="polite">

    <template x-for="item in toasts" :key="item.id">
        <div x-show="item.visible"
             x-transition:enter="transform ease-out duration-300 transition"
             x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-4"
             x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="pointer-events-auto w-full bg-white dark:bg-gray-800 rounded-xl shadow-lg border p-4 flex items-start gap-3 relative overflow-hidden"
             :class="{
                 'border-success-200 dark:border-success-800/60': item.type === 'success',
                 'border-error-200 dark:border-error-800/60': item.type === 'error',
                 'border-warning-200 dark:border-warning-800/60': item.type === 'warning',
                 'border-brand-200 dark:border-brand-800/60': item.type === 'info'
             }">

            <!-- Icon -->
            <div class="flex-shrink-0 mt-0.5">
                <template x-if="item.type === 'success'">
                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-success-100 dark:bg-success-900/40 text-success-600 dark:text-success-400">
                        <i class="fa-solid fa-circle-check text-base"></i>
                    </span>
                </template>
                <template x-if="item.type === 'error'">
                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-error-100 dark:bg-error-900/40 text-error-600 dark:text-error-400">
                        <i class="fa-solid fa-circle-xmark text-base"></i>
                    </span>
                </template>
                <template x-if="item.type === 'warning'">
                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-warning-100 dark:bg-warning-900/40 text-warning-600 dark:text-warning-400">
                        <i class="fa-solid fa-triangle-exclamation text-base"></i>
                    </span>
                </template>
                <template x-if="item.type === 'info'">
                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-brand-100 dark:bg-brand-900/40 text-brand-600 dark:text-brand-200">
                        <i class="fa-solid fa-circle-info text-base"></i>
                    </span>
                </template>
            </div>

            <!-- Content -->
            <div class="flex-1 min-w-0 pr-4">
                <h4 class="text-sm font-semibold text-gray-900 dark:text-white capitalize" x-text="item.title || item.type"></h4>
                <p class="text-xs text-gray-600 dark:text-gray-300 mt-0.5 leading-relaxed break-words" x-text="item.message"></p>
            </div>

            <!-- Close Button -->
            <button @click="removeToast(item.id)"
                    type="button"
                    class="flex-shrink-0 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-colors p-1 rounded-lg">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>
    </template>
</div>

<script>
    function toastManager() {
        return {
            toasts: [],
            counter: 0,
            init() {
                // Flash messages from Laravel Session
                @if (session('success'))
                    this.addToast({ type: 'success', title: 'Berhasil', message: @json(session('success')) });
                @endif

                @if (session('error'))
                    this.addToast({ type: 'error', title: 'Terjadi Kesalahan', message: @json(session('error')) });
                @endif

                @if (session('warning'))
                    this.addToast({ type: 'warning', title: 'Perhatian', message: @json(session('warning')) });
                @endif

                @if (session('info'))
                    this.addToast({ type: 'info', title: 'Informasi', message: @json(session('info')) });
                @endif

                @if ($errors->any())
                    @foreach ($errors->all() as $err)
                        this.addToast({ type: 'error', title: 'Validasi Gagal', message: @json($err) });
                    @endforeach
                @endif

                // Global JS helper
                window.toast = {
                    success: (msg, title = 'Berhasil') => window.dispatchEvent(new CustomEvent('toast', { detail: { type: 'success', title, message: msg } })),
                    error: (msg, title = 'Terjadi Kesalahan') => window.dispatchEvent(new CustomEvent('toast', { detail: { type: 'error', title, message: msg } })),
                    warning: (msg, title = 'Perhatian') => window.dispatchEvent(new CustomEvent('toast', { detail: { type: 'warning', title, message: msg } })),
                    info: (msg, title = 'Informasi') => window.dispatchEvent(new CustomEvent('toast', { detail: { type: 'info', title, message: msg } }))
                };
            },
            addToast({ type = 'info', title = '', message = '', duration = 4500 }) {
                const id = ++this.counter;
                const toast = { id, type, title, message, visible: true };
                this.toasts.push(toast);

                setTimeout(() => {
                    this.removeToast(id);
                }, duration);
            },
            removeToast(id) {
                const index = this.toasts.findIndex(t => t.id === id);
                if (index !== -1) {
                    this.toasts[index].visible = false;
                    setTimeout(() => {
                        this.toasts = this.toasts.filter(t => t.id !== id);
                    }, 300);
                }
            }
        };
    }
</script>
