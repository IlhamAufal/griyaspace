@extends('layouts.app')

@section('content')
<div class="space-y-6">

    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Notifikasi</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                @if($unreadCount > 0)
                    Anda memiliki <strong class="text-brand-500">{{ $unreadCount }}</strong> notifikasi belum dibaca
                @else
                    Semua notifikasi sudah dibaca
                @endif
            </p>
        </div>
        @if($unreadCount > 0)
            <button onclick="markAllRead()" class="inline-flex items-center gap-2 bg-brand-500 hover:bg-brand-600 text-white px-4 py-2.5 rounded-lg text-sm font-medium shadow-theme-xs transition-colors">
                <i class="fa-solid fa-check-double text-sm"></i>
                <span>Tandai Semua Dibaca</span>
            </button>
        @endif
    </div>

    <!-- Filter Tabs -->
    <div class="flex items-center gap-2 overflow-x-auto pb-1">
        <a href="{{ route('notifications.index', ['filter' => 'all']) }}"
            class="px-4 py-2 rounded-lg text-sm font-medium transition-colors whitespace-nowrap {{ $filter === 'all' ? 'bg-brand-500 text-white shadow-sm' : 'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 border border-gray-200 dark:border-gray-700' }}">
            Semua
        </a>
        <a href="{{ route('notifications.index', ['filter' => 'unread']) }}"
            class="px-4 py-2 rounded-lg text-sm font-medium transition-colors whitespace-nowrap {{ $filter === 'unread' ? 'bg-brand-500 text-white shadow-sm' : 'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 border border-gray-200 dark:border-gray-700' }}">
            Belum Dibaca
        </a>
        <a href="{{ route('notifications.index', ['filter' => 'read']) }}"
            class="px-4 py-2 rounded-lg text-sm font-medium transition-colors whitespace-nowrap {{ $filter === 'read' ? 'bg-brand-500 text-white shadow-sm' : 'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 border border-gray-200 dark:border-gray-700' }}">
            Sudah Dibaca
        </a>
        <span class="w-px h-6 bg-gray-200 dark:bg-gray-700"></span>
        <a href="{{ route('notifications.index', ['filter' => 'booking']) }}"
            class="px-4 py-2 rounded-lg text-sm font-medium transition-colors whitespace-nowrap {{ $filter === 'booking' ? 'bg-brand-500 text-white shadow-sm' : 'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 border border-gray-200 dark:border-gray-700' }}">
            <i class="fa-solid fa-calendar-check mr-1.5 text-xs"></i> Booking
        </a>
        <a href="{{ route('notifications.index', ['filter' => 'permit']) }}"
            class="px-4 py-2 rounded-lg text-sm font-medium transition-colors whitespace-nowrap {{ $filter === 'permit' ? 'bg-brand-500 text-white shadow-sm' : 'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 border border-gray-200 dark:border-gray-700' }}">
            <i class="fa-solid fa-file-invoice mr-1.5 text-xs"></i> Permit
        </a>
    </div>

    <!-- Notification List -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700/60 shadow-xs overflow-hidden">
        @forelse($notifications as $notification)
            @php
                $type = class_basename($notification->type);
                $data = $notification->data;
                $isUnread = is_null($notification->read_at);
            @endphp
            <div id="notif-{{ $notification->id }}"
                class="flex items-start gap-4 px-5 py-4 border-b border-gray-100 dark:border-gray-700/60 transition-colors {{ $isUnread ? 'bg-brand-50/30 dark:bg-brand-500/5' : 'hover:bg-gray-50 dark:hover:bg-gray-700/30' }}"
                x-data="{ read: {{ $isUnread ? 'false' : 'true' }} }">

                <!-- Icon -->
                <div class="flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center mt-0.5
                    @if($type === 'BookingSubmittedNotification') bg-blue-50 dark:bg-blue-500/10
                    @elseif($type === 'BookingApprovedNotification') bg-green-50 dark:bg-green-500/10
                    @elseif($type === 'BookingRejectedNotification') bg-red-50 dark:bg-red-500/10
                    @elseif($type === 'BookingRevisionNotification') bg-orange-50 dark:bg-orange-500/10
                    @elseif($type === 'BookingCancelledNotification') bg-gray-100 dark:bg-gray-700
                    @elseif($type === 'PermitGeneratedNotification') bg-teal-50 dark:bg-teal-500/10
                    @else bg-brand-50 dark:bg-brand-500/10 @endif">
                    @if($type === 'BookingSubmittedNotification')
                        <i class="fa-solid fa-paper-plane text-blue-500"></i>
                    @elseif($type === 'BookingApprovedNotification')
                        <i class="fa-solid fa-circle-check text-green-500"></i>
                    @elseif($type === 'BookingRejectedNotification')
                        <i class="fa-solid fa-circle-xmark text-red-500"></i>
                    @elseif($type === 'BookingRevisionNotification')
                        <i class="fa-solid fa-pen-to-square text-orange-500"></i>
                    @elseif($type === 'BookingCancelledNotification')
                        <i class="fa-solid fa-ban text-gray-500"></i>
                    @elseif($type === 'PermitGeneratedNotification')
                        <i class="fa-solid fa-file-invoice text-teal-500"></i>
                    @else
                        <i class="fa-solid fa-bell text-brand-500"></i>
                    @endif
                </div>

                <!-- Content -->
                <div class="flex-1 min-w-0">
                    <p class="text-sm text-gray-800 dark:text-gray-200 leading-relaxed">
                        {{ $data['message'] ?? 'Notifikasi baru' }}
                    </p>
                    <div class="flex items-center gap-3 mt-1.5">
                        <span class="text-xs text-gray-400 dark:text-gray-500">
                            {{ $notification->created_at->diffForHumans() }}
                        </span>
                        @if(isset($data['booking_number']))
                            <span class="text-xs font-mono text-gray-400 dark:text-gray-500 bg-gray-100 dark:bg-gray-700 px-1.5 py-0.5 rounded">
                                {{ $data['booking_number'] }}
                            </span>
                        @endif
                        @if(isset($data['booking_id']))
                            <a href="{{ route('bookings.show', $data['booking_id']) }}" class="text-xs text-brand-500 hover:text-brand-600 font-medium transition-colors">
                                Lihat Detail <i class="fa-solid fa-arrow-right text-[10px] ml-0.5"></i>
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Unread Dot + Mark Read Button -->
                <div class="flex items-center gap-2 flex-shrink-0">
                    <template x-if="!read">
                        <button @click="markNotifRead('{{ $notification->id }}')" title="Tandai sudah dibaca"
                            class="w-8 h-8 rounded-lg flex items-center justify-center text-gray-400 hover:text-brand-500 hover:bg-brand-50 dark:hover:bg-brand-500/10 transition-colors">
                            <i class="fa-regular fa-envelope-open text-sm"></i>
                        </button>
                    </template>
                    <template x-if="read">
                        <span class="w-8 h-8 flex items-center justify-center">
                            <i class="fa-solid fa-check text-[10px] text-green-400"></i>
                        </span>
                    </template>
                </div>
            </div>
        @empty
            <div class="px-5 py-16 text-center">
                <div class="w-16 h-16 mx-auto rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center mb-4">
                    <i class="fa-regular fa-bell-slash text-3xl text-gray-300 dark:text-gray-500"></i>
                </div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Tidak ada notifikasi</p>
                <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Notifikasi akan muncul di sini</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($notifications->hasPages())
        <div class="flex items-center justify-between">
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Menampilkan {{ $notifications->firstItem() }}-{{ $notifications->lastItem() }} dari {{ $notifications->total() }} notifikasi
            </p>
            <div class="flex items-center gap-1">
                @if($notifications->onFirstPage())
                    <span class="px-3 py-2 rounded-lg text-sm font-medium text-gray-300 dark:text-gray-600 bg-gray-100 dark:bg-gray-800 cursor-not-allowed">
                        <i class="fa-solid fa-chevron-left text-xs"></i>
                    </span>
                @else
                    <a href="{{ $notifications->previousPageUrl() }}&filter={{ $filter }}" class="px-3 py-2 rounded-lg text-sm font-medium text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 border border-gray-200 dark:border-gray-700 transition-colors">
                        <i class="fa-solid fa-chevron-left text-xs"></i>
                    </a>
                @endif

                @foreach($notifications->getUrlRange(max(1, $notifications->currentPage() - 2), min($notifications->lastPage(), $notifications->currentPage() + 2)) as $page => $url)
                    <a href="{{ $url }}&filter={{ $filter }}"
                        class="w-9 h-9 rounded-lg text-sm font-medium flex items-center justify-center transition-colors
                        {{ $page == $notifications->currentPage() ? 'bg-brand-500 text-white shadow-sm' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 border border-gray-200 dark:border-gray-700' }}">
                        {{ $page }}
                    </a>
                @endforeach

                @if($notifications->hasMorePages())
                    <a href="{{ $notifications->nextPageUrl() }}&filter={{ $filter }}" class="px-3 py-2 rounded-lg text-sm font-medium text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 border border-gray-200 dark:border-gray-700 transition-colors">
                        <i class="fa-solid fa-chevron-right text-xs"></i>
                    </a>
                @else
                    <span class="px-3 py-2 rounded-lg text-sm font-medium text-gray-300 dark:text-gray-600 bg-gray-100 dark:bg-gray-800 cursor-not-allowed">
                        <i class="fa-solid fa-chevron-right text-xs"></i>
                    </span>
                @endif
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script>
async function markNotifRead(id) {
    try {
        const res = await fetch(`/notifications/${id}/read`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        });
        if (res.ok) {
            const el = document.getElementById('notif-' + id);
            if (el) {
                el.classList.remove('bg-brand-50/30', 'dark:bg-brand-500/5');
                el.classList.add('hover:bg-gray-50', 'dark:hover:bg-gray-700/30');
            }
            // Update Alpine component
            const Alpine = window.Alpine;
            if (Alpine) {
                Alpine.evaluate(el, 'read = true');
            }
            // Update header badge
            updateHeaderBadge();
        }
    } catch (e) {
        console.error('Gagal menandai notifikasi', e);
    }
}

async function markAllRead() {
    try {
        const res = await fetch('{{ route("notifications.markAllAsRead") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        });
        if (res.ok) {
            location.reload();
        }
    } catch (e) {
        console.error('Gagal menandai semua notifikasi', e);
    }
}

function updateHeaderBadge() {
    // Dispatch event agar header bell icon update
    window.dispatchEvent(new Event('notification-read'));
}
</script>
@endpush
@endsection
