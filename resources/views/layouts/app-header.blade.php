@php
    $user = auth()->user();
@endphp

<header
    class="sticky top-0 flex w-full bg-white border-gray-200 z-99999 dark:border-gray-800 dark:bg-gray-900 xl:border-b"
>
    <div class="flex flex-col items-center justify-between grow xl:flex-row xl:px-6">
        <div
            class="flex items-center justify-between w-full gap-2 px-3 py-3 border-b border-gray-200 dark:border-gray-800 sm:gap-4 xl:justify-normal xl:border-b-0 xl:px-0 lg:py-4">

            <!-- Desktop Sidebar Toggle Button -->
            <button
                class="hidden xl:flex items-center justify-center w-10 h-10 text-gray-500 border border-gray-200 rounded-lg dark:border-gray-800 dark:text-gray-400 lg:h-11 lg:w-11"
                :class="{ 'bg-gray-100 dark:bg-white/[0.03]': !$store.sidebar.isExpanded }"
                @click="$store.sidebar.toggleExpanded()" aria-label="Toggle Sidebar">
                <i x-show="!$store.sidebar.isMobileOpen" class="fa-solid fa-bars text-sm"></i>
                <i x-show="$store.sidebar.isMobileOpen" class="fa-solid fa-xmark text-lg"></i>
            </button>

            <!-- Mobile Menu Toggle Button -->
            <button
                class="flex xl:hidden items-center justify-center w-10 h-10 text-gray-500 rounded-lg dark:text-gray-400 lg:h-11 lg:w-11"
                :class="{ 'bg-gray-100 dark:bg-white/[0.03]': $store.sidebar.isMobileOpen }"
                @click="$store.sidebar.toggleMobileOpen()" aria-label="Toggle Mobile Menu">
                <i x-show="!$store.sidebar.isMobileOpen" class="fa-solid fa-bars text-sm"></i>
                <i x-show="$store.sidebar.isMobileOpen" class="fa-solid fa-xmark text-lg"></i>
            </button>

            <!-- Logo (mobile only) -->
            <a href="/" class="xl:hidden">
                <img class="dark:hidden h-7 w-auto" src="/images/logo/dkpti-dark.svg" alt="Logo" />
                <img class="hidden dark:block h-7 w-auto" src="/images/logo/dkpti-light.svg" alt="Logo" />
            </a>

            <!-- Application Menu Toggle (mobile only) -->
            <button @click="toggleApplicationMenu()"
                class="flex items-center justify-center w-10 h-10 text-gray-700 rounded-lg z-99999 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-800 xl:hidden">
                <i class="fa-solid fa-ellipsis-vertical text-lg"></i>
            </button>
        </div>

        <!-- Right Side Actions -->
        <div :class="isApplicationMenuOpen ? 'flex' : 'hidden'"
            class="items-center justify-between w-full gap-4 px-5 py-4 xl:flex shadow-theme-md xl:justify-end xl:px-0 xl:shadow-none">
            <div class="flex items-center gap-2 2xsm:gap-3">
                <!-- User Info -->
                @if ($user)
                    <div class="flex items-center gap-3">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $user->name }}</span>
                        <span class="rounded-full bg-brand-50 px-2.5 py-0.5 text-xs font-medium text-brand-600 dark:bg-brand-500/10 dark:text-brand-300">
                            {{ $user->role->name ?? '-' }}
                        </span>
                    </div>
                @endif

                <!-- Theme Toggle Switch -->
                <button
                    type="button"
                    class="relative inline-flex h-9 w-16 items-center justify-between rounded-full bg-gray-100 p-1 border border-gray-200 transition-colors dark:bg-gray-800 dark:border-gray-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-500 focus-visible:ring-offset-2 dark:focus-visible:ring-offset-gray-900"
                    @click="$store.theme.toggle()"
                    :aria-label="$store.theme.theme === 'dark' ? 'Ganti ke mode terang' : 'Ganti ke mode gelap'"
                    :title="$store.theme.theme === 'dark' ? 'Mode Gelap (Klik untuk Terang)' : 'Mode Terang (Klik untuk Gelap)'">
                    
                    <!-- Sliding Thumb -->
                    <span
                        class="absolute top-1 left-1 flex h-7 w-7 items-center justify-center rounded-full bg-white shadow-theme-xs transition-transform duration-300 ease-in-out dark:bg-gray-900 border border-gray-100 dark:border-gray-800"
                        :class="$store.theme.theme === 'dark' ? 'translate-x-7' : 'translate-x-0'">
                    </span>

                    <!-- Sun Icon (Light Mode) -->
                    <span class="z-10 flex h-7 w-7 items-center justify-center text-xs transition-colors duration-200"
                        :class="$store.theme.theme === 'light' ? 'text-warning-500 font-semibold' : 'text-gray-500 dark:text-gray-400'">
                        <i class="fa-solid fa-sun text-sm"></i>
                    </span>

                    <!-- Moon Icon (Dark Mode) -->
                    <span class="z-10 flex h-7 w-7 items-center justify-center text-xs transition-colors duration-200"
                        :class="$store.theme.theme === 'dark' ? 'text-brand-300 font-semibold' : 'text-gray-500 dark:text-gray-400'">
                        <i class="fa-solid fa-moon text-sm"></i>
                    </span>
                </button>

                <!-- Logout -->
                <button type="button" aria-haspopup="dialog"
                    @click="$dispatch('open-modal', 'logout-confirm')"
                    class="flex items-center gap-2 rounded-lg border border-gray-200 px-4 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-100 dark:border-gray-800 dark:text-gray-300 dark:hover:bg-gray-800">
                    <i class="fa-solid fa-arrow-right-from-bracket text-base"></i>
                    Keluar
                </button>
            </div>
        </div>
    </div>
</header>

<x-common.modal id="logout-confirm" title="Keluar dari akun?" icon="fa-solid fa-arrow-right-from-bracket" maxWidth="sm">
    <p>Sesi kamu akan berakhir dan kamu akan kembali ke halaman masuk. Pastikan semua pekerjaan sudah tersimpan.</p>

    <x-slot:footer>
        <form method="POST" action="{{ route('logout') }}" class="contents">
            @csrf
            <button type="button" @click="$dispatch('close-modal', 'logout-confirm')"
                class="rounded-lg border border-gray-300 px-4 py-3 text-sm font-medium text-gray-700 transition-colors hover:bg-gray-100 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">
                Batal
            </button>
            <button type="submit"
                class="rounded-lg bg-error-600 px-4 py-3 text-sm font-medium text-white transition-colors hover:bg-error-700 focus:outline-hidden focus:ring-3 focus:ring-error-500/30">
                Keluar
            </button>
        </form>
    </x-slot:footer>
</x-common.modal>


