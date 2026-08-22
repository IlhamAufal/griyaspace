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
                 'border-green-200 dark:border-green-800/60': item.type === 'success',
                 'border-red-200 dark:border-red-800/60': item.type === 'error',
                 'border-amber-200 dark:border-amber-800/60': item.type === 'warning',
                 'border-blue-200 dark:border-blue-800/60': item.type === 'info'
             }">

            <!-- Icon -->
            <div class="flex-shrink-0 mt-0.5">
                <template x-if="item.type === 'success'">
                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-green-100 dark:bg-green-900/40 text-green-600 dark:text-green-400">
                        <i class="fa-solid fa-circle-check text-base"></i>
                    </span>
                </template>
                <template x-if="item.type === 'error'">
                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-red-100 dark:bg-red-900/40 text-red-600 dark:text-red-400">
                        <i class="fa-solid fa-circle-xmark text-base"></i>
                    </span>
                </template>
                <template x-if="item.type === 'warning'">
                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-amber-100 dark:bg-amber-900/40 text-amber-600 dark:text-amber-400">
                        <i class="fa-solid fa-triangle-exclamation text-base"></i>
                    </span>
                </template>
                <template x-if="item.type === 'info'">
                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-blue-100 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400">
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
                    class="flex-shrink-0 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors p-1 rounded-lg">
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
