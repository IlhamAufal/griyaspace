@php
    $user = auth()->user();
@endphp

<header
    class="sticky top-0 flex w-full bg-white border-gray-200 z-99999 dark:border-gray-800 dark:bg-gray-900 xl:border-b"
    x-data="notificationBell()" x-init="init()">
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
                <img class="dark:hidden" src="/images/logo/logo.svg" alt="Logo" />
                <img class="hidden dark:block" src="/images/logo/logo-dark.svg" alt="Logo" />
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
                <!-- Notification Bell -->
                @if ($user)
                    <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                        <button @click="open = !open; if (open) fetchNotifications()"
                            class="relative flex items-center justify-center w-10 h-10 text-gray-500 rounded-lg hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-800 transition-colors">
                            <i class="fa-solid fa-bell text-base"></i>
                            <span x-show="unreadCount > 0" x-text="unreadCount"
                                class="absolute -top-0.5 -right-0.5 flex items-center justify-center w-5 h-5 text-[10px] font-bold text-white bg-red-500 rounded-full ring-2 ring-white dark:ring-gray-900">
                            </span>
                        </button>

                        <!-- Dropdown -->
                        <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-1"
                            class="absolute right-0 mt-2 w-80 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-lg z-50 overflow-hidden">

                            <!-- Header -->
                            <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100 dark:border-gray-700">
                                <h3 class="text-sm font-bold text-gray-900 dark:text-white">Notifikasi</h3>
                                <button x-show="unreadCount > 0" @click="markAllAsRead()"
                                    class="text-xs text-brand-500 hover:text-brand-600 font-medium transition-colors">
                                    Tandai semua dibaca
                                </button>
                            </div>

                            <!-- Notification List -->
                            <div class="max-h-80 overflow-y-auto divide-y divide-gray-50 dark:divide-gray-700/50">
                                <template x-if="notifications.length === 0">
                                    <div class="px-4 py-8 text-center">
                                        <i class="fa-regular fa-bell-slash text-3xl text-gray-300 dark:text-gray-600 mb-2 block"></i>
                                        <p class="text-sm text-gray-400 dark:text-gray-500">Belum ada notifikasi</p>
                                    </div>
                                </template>
                                <template x-for="n in notifications" :key="n.id">
                                    <div @click="markAsRead(n.id)"
                                        class="flex gap-3 px-4 py-3 cursor-pointer transition-colors"
                                        :class="n.read_at ? 'bg-white dark:bg-gray-800' : 'bg-brand-50/50 dark:bg-brand-500/5 hover:bg-brand-50 dark:hover:bg-brand-500/10'">
                                        <div class="flex-shrink-0 w-9 h-9 rounded-full flex items-center justify-center"
                                            :class="getIconBg(n.type)">
                                            <i :class="getIcon(n.type)" class="text-sm"></i>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <p class="text-sm font-medium text-gray-800 dark:text-gray-200 line-clamp-2" x-text="n.data.message"></p>
                                            <p class="text-[11px] text-gray-400 dark:text-gray-500 mt-1" x-text="n.time_ago"></p>
                                        </div>
                                        <div x-show="!n.read_at" class="flex-shrink-0 mt-1.5">
                                            <span class="w-2 h-2 rounded-full bg-brand-500 block"></span>
                                        </div>
                                    </div>
                                </template>
                            </div>

                            <!-- Footer -->
                            <div class="px-4 py-2.5 border-t border-gray-100 dark:border-gray-700 text-center">
                                <a href="{{ route('notifications.index') }}" class="text-xs font-medium text-brand-500 hover:text-brand-600 transition-colors">
                                    Lihat Semua Notifikasi
                                </a>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- User Info -->
                @if ($user)
                    <div class="flex items-center gap-3">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $user->name }}</span>
                        <span class="rounded-full bg-brand-50 px-2.5 py-0.5 text-xs font-medium text-brand-600 dark:bg-brand-500/10 dark:text-brand-400">
                            {{ $user->role->name ?? '-' }}
                        </span>
                    </div>
                @endif

                <!-- Theme Toggle Switch -->
                <button
                    type="button"
                    class="relative inline-flex h-9 w-16 items-center justify-between rounded-full bg-gray-100 p-1 border border-gray-200 transition-colors dark:bg-gray-800 dark:border-gray-700 focus:outline-none"
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
                        :class="$store.theme.theme === 'light' ? 'text-amber-500 font-semibold' : 'text-gray-400 dark:text-gray-500'">
                        <i class="fa-solid fa-sun text-sm"></i>
                    </span>

                    <!-- Moon Icon (Dark Mode) -->
                    <span class="z-10 flex h-7 w-7 items-center justify-center text-xs transition-colors duration-200"
                        :class="$store.theme.theme === 'dark' ? 'text-blue-400 font-semibold' : 'text-gray-400 dark:text-gray-500'">
                        <i class="fa-solid fa-moon text-sm"></i>
                    </span>
                </button>

                <!-- Logout -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="flex items-center gap-2 rounded-lg border border-gray-200 px-4 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-100 dark:border-gray-800 dark:text-gray-300 dark:hover:bg-gray-800">
                        <i class="fa-solid fa-arrow-right-from-bracket text-base"></i>
                        Keluar
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>

@push('scripts')
<script>
function notificationBell() {
    return {
        notifications: [],
        unreadCount: 0,
        loading: false,

        init() {
            this.fetchNotifications();
            // Poll setiap 30 detik
            setInterval(() => this.fetchNotifications(), 30000);
            // Listen untuk event dari notifications page
            window.addEventListener('notification-read', () => this.fetchNotifications());
        },

        async fetchNotifications() {
            try {
                const res = await fetch('{{ route("notifications.api") }}', {
                    headers: { 'Accept': 'application/json' }
                });
                const data = await res.json();
                this.notifications = data.notifications;
                this.unreadCount = data.unread_count;
            } catch (e) {
                console.error('Gagal memuat notifikasi', e);
            }
        },

        async markAsRead(id) {
            try {
                const res = await fetch(`/notifications/${id}/read`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                });
                const data = await res.json();
                this.unreadCount = data.unread_count;
                const notif = this.notifications.find(n => n.id === id);
                if (notif) notif.read_at = new Date().toISOString();
            } catch (e) {
                console.error('Gagal menandai notifikasi', e);
            }
        },

        async markAllAsRead() {
            try {
                const res = await fetch('{{ route("notifications.markAllAsRead") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                });
                const data = await res.json();
                this.unreadCount = data.unread_count;
                this.notifications.forEach(n => n.read_at = new Date().toISOString());
            } catch (e) {
                console.error('Gagal menandai semua notifikasi', e);
            }
        },

        getIcon(type) {
            const icons = {
                'App\\Notifications\\BookingSubmittedNotification': 'fa-solid fa-paper-plane text-blue-500',
                'App\\Notifications\\BookingApprovedNotification': 'fa-solid fa-circle-check text-green-500',
                'App\\Notifications\\BookingRejectedNotification': 'fa-solid fa-circle-xmark text-red-500',
                'App\\Notifications\\BookingRevisionNotification': 'fa-solid fa-pen-to-square text-orange-500',
                'App\\Notifications\\BookingCancelledNotification': 'fa-solid fa-ban text-gray-500',
                'App\\Notifications\\PermitGeneratedNotification': 'fa-solid fa-file-invoice text-teal-500',
            };
            return icons[type] || 'fa-solid fa-bell text-brand-500';
        },

        getIconBg(type) {
            const bgs = {
                'App\\Notifications\\BookingSubmittedNotification': 'bg-blue-50 dark:bg-blue-500/10',
                'App\\Notifications\\BookingApprovedNotification': 'bg-green-50 dark:bg-green-500/10',
                'App\\Notifications\\BookingRejectedNotification': 'bg-red-50 dark:bg-red-500/10',
                'App\\Notifications\\BookingRevisionNotification': 'bg-orange-50 dark:bg-orange-500/10',
                'App\\Notifications\\BookingCancelledNotification': 'bg-gray-100 dark:bg-gray-700',
                'App\\Notifications\\PermitGeneratedNotification': 'bg-teal-50 dark:bg-teal-500/10',
            };
            return bgs[type] || 'bg-brand-50 dark:bg-brand-500/10';
        }
    };
}
</script>
@endpush
