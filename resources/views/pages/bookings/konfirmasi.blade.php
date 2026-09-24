@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="flex items-center gap-2 text-sm text-gray-500 mb-6">
            <a href="{{ route('dashboard') }}" class="hover:text-blue-600 transition-colors"><i class="fa-solid fa-house"></i></a>
            <i class="fa-solid fa-chevron-right text-xs"></i>
            <span class="text-gray-900 font-medium">Konfirmasi Pengajuan</span>
        </nav>

        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Konfirmasi Pengajuan</h1>
        </div>

        <div x-data="{
            search: '{{ request('search') }}',
            filters: { status: '{{ request('status') }}', user_id: '{{ request('user_id') }}' },
            sort: '{{ request('sort') }}',
            direction: '{{ request('direction', 'asc') }}',
            loading: false,
            timer: null,
            doSearch() {
                clearTimeout(this.timer);
                this.timer = setTimeout(() => { this.page(1); }, 400);
            },
            applyFilter() { this.page(1); },
            clearFilters() {
                this.search = '';
                this.filters = { status: '', user_id: '' };
                this.page(1);
            },
            sortColumn(col, dir) {
                this.sort = col;
                this.direction = dir;
                this.page(1);
            },
            page(p) {
                this.loading = true;
                const params = new URLSearchParams();
                if (this.search) params.set('search', this.search);
                if (this.filters.status) params.set('status', this.filters.status);
                if (this.filters.user_id) params.set('user_id', this.filters.user_id);
                if (this.sort) params.set('sort', this.sort);
                if (this.direction) params.set('direction', this.direction);
                params.set('page', p);
                const url = '{{ route('bookings.konfirmasi') }}?' + params.toString();
                fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                    .then(r => r.text())
                    .then(html => {
                        const doc = new DOMParser().parseFromString(html, 'text/html');
                        const newTable = doc.querySelector('#table-area');
                        if (newTable) document.querySelector('#table-area').innerHTML = newTable.innerHTML;
                        history.replaceState(null, '', url);
                        this.loading = false;
                        this.$nextTick(() => {
                            document.querySelectorAll('#table-area a.pagination-link').forEach(link => {
                                link.addEventListener('click', e => {
                                    e.preventDefault();
                                    const page = new URL(link.href).searchParams.get('page') || 1;
                                    this.page(page);
                                });
                            });
                        });
                    })
                    .catch(() => { this.loading = false; });
            }
        }" x-init="$nextTick(() => {
            document.querySelectorAll('#table-area a.pagination-link').forEach(link => {
                link.addEventListener('click', e => {
                    e.preventDefault();
                    const page = new URL(link.href).searchParams.get('page') || 1;
                    this.page(page);
                });
            });
        })" @sort.window="sortColumn($event.detail.column, $event.detail.direction)">

            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-200 dark:border-gray-700/60 p-4 mb-5">
                <div class="flex flex-wrap items-end gap-4">
                    <div class="flex-1 min-w-[220px]">
                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">Pencarian</label>
                        <div class="relative">
                            <input type="text" x-model="search" @input.debounce.400ms="doSearch()" placeholder="No. booking, nama kegiatan, pengaju..." class="h-10 w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm bg-transparent placeholder:text-gray-500 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 shadow-theme-xs">
                            <i x-show="!loading" class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-500 dark:text-gray-400 text-xs"></i>
                            <i x-show="loading" x-cloak class="fa-solid fa-spinner fa-spin absolute left-3.5 top-1/2 -translate-y-1/2 text-brand-500 text-xs"></i>
                        </div>
                    </div>
                    <div class="w-48">
                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">Status</label>
                        <select x-model="filters.status" @change="applyFilter()" class="h-10 w-full border border-gray-300 rounded-lg text-sm px-3 py-2 bg-transparent focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 shadow-theme-xs">
                            <option value="">Semua</option>
                            <option value="submitted">Diajukan</option>
                            <option value="revision">Revisi</option>
                        </select>
                    </div>
                    <div class="w-56">
                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">Pengaju</label>
                        <select x-model="filters.user_id" @change="applyFilter()" class="h-10 w-full border border-gray-300 rounded-lg text-sm px-3 py-2 bg-transparent focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 shadow-theme-xs">
                            <option value="">Semua Pengaju</option>
                            @foreach($users as $u)
                            <option value="{{ $u->id }}">{{ $u->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="button" @click="clearFilters()" x-show="search || filters.status || filters.user_id" x-cloak class="h-10 bg-gray-100 hover:bg-gray-200 text-gray-700 dark:bg-gray-700 dark:text-gray-200 px-4 rounded-lg text-sm font-medium inline-flex items-center gap-2 transition-colors">
                        <i class="fa-solid fa-xmark text-xs"></i> Reset
                    </button>
                </div>
            </div>

            <div id="table-area">
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-200 dark:border-gray-700/60 overflow-hidden">
                    <div class="overflow-x-auto transition-opacity duration-300" :class="loading ? 'opacity-50 pointer-events-none' : ''">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-brand-500 text-white">
                                <tr>
                                    <x-table.sortable-header column="booking_number" label="No. Booking" />
                                    <th class="px-6 py-3.5 text-left text-xs font-bold text-white uppercase tracking-wider">Pengaju</th>
                                    <th class="px-6 py-3.5 text-left text-xs font-bold text-white uppercase tracking-wider">Ruangan</th>
                                    <x-table.sortable-header column="activity_name" label="Kegiatan" />
                                    <x-table.sortable-header column="booking_date" label="Tanggal" />
                                    <x-table.sortable-header column="status" label="Status" />
                                    <th class="px-6 py-3.5 text-center text-xs font-bold text-white uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($bookings as $booking)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">{{ $booking->booking_number ?? '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">{{ $booking->submittedBy->name ?? '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">{{ $booking->room->name }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-800 dark:text-gray-200 font-medium" title="{{ $booking->activity_name }}">{{ $booking->activity_name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">{{ $booking->booking_date->format('d/m/Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $booking->status->colorClasses() }}">
                                            {{ $booking->status->label() }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                        <div class="flex items-center justify-center gap-1.5">
                                            <a href="{{ route('bookings.show', $booking) }}" class="inline-flex items-center justify-center w-8 h-8 text-blue-600 hover:text-blue-900 hover:bg-blue-50 dark:hover:bg-blue-900/30 rounded-lg transition-colors" title="Lihat Detail">
                                                <i class="fa-solid fa-eye text-sm"></i>
                                            </a>
                                            @if($booking->status !== \App\Enums\BookingStatus::Revision)
                                            <button type="button" onclick="openDecisionModal('{{ $booking->id }}', '{{ $booking->booking_number }}', '{{ $booking->activity_name }}')" class="inline-flex items-center justify-center w-8 h-8 text-green-600 hover:text-green-900 hover:bg-green-50 dark:hover:bg-green-900/30 rounded-lg transition-colors" title="Keputusan">
                                                <i class="fa-solid fa-gavel text-sm"></i>
                                            </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-8 text-center text-sm text-gray-500 dark:text-gray-400">Tidak ada pengajuan yang perlu dikonfirmasi</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="mt-4">
                    {{ $bookings->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<div id="decisionModal" class="fixed inset-0 z-[99999] hidden bg-black/50" style="display:none;">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl w-full max-w-lg overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 bg-brand-500 text-white">
                <div class="flex items-center gap-3">
                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-white/15 text-white">
                        <i class="fa-solid fa-gavel text-sm"></i>
                    </span>
                    <h3 class="text-lg font-bold text-white" id="decisionModalTitle">Keputusan Pengajuan</h3>
                </div>
                <button onclick="closeDecisionModal()" type="button" class="text-white/80 hover:text-white hover:bg-white/15 p-1.5 rounded-lg transition-colors cursor-pointer" aria-label="Tutup">
                    <i class="fa-solid fa-xmark text-lg text-white"></i>
                </button>
            </div>
            <form id="decisionForm" method="POST" class="p-6">
                @csrf
                <input type="hidden" name="action" id="decisionAction" value="">
                <div class="mb-2">
                    <p class="text-sm text-gray-600 dark:text-gray-400" id="decisionModalDesc"></p>
                </div>
                <div class="mb-4">
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">No. Booking:</p>
                    <p class="text-sm font-semibold text-gray-900 dark:text-white" id="decisionBookingNumber"></p>
                </div>
                <div class="mb-4">
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Kegiatan:</p>
                    <p class="text-sm font-semibold text-gray-900 dark:text-white" id="decisionActivityName"></p>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Keputusan <span class="text-red-500">*</span></label>
                    <div class="flex gap-3">
                        <button type="button" onclick="setDecision('approve')" class="decision-action-btn flex-1 inline-flex items-center justify-center gap-2 border border-green-300 bg-green-50 hover:bg-green-100 text-green-700 px-4 py-3 rounded-lg text-sm font-medium transition-colors">
                            <i class="fa-solid fa-check"></i> Setujui
                        </button>
                        <button type="button" onclick="setDecision('revision')" class="decision-action-btn flex-1 inline-flex items-center justify-center gap-2 border border-orange-300 bg-orange-50 hover:bg-orange-100 text-orange-700 px-4 py-3 rounded-lg text-sm font-medium transition-colors">
                            <i class="fa-solid fa-rotate-left"></i> Revisi
                        </button>
                        <button type="button" onclick="setDecision('reject')" class="decision-action-btn flex-1 inline-flex items-center justify-center gap-2 border border-red-300 bg-red-50 hover:bg-red-100 text-red-700 px-4 py-3 rounded-lg text-sm font-medium transition-colors">
                            <i class="fa-solid fa-xmark"></i> Tolak
                        </button>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Catatan <span id="requiredIndicator" class="text-red-500">*</span></label>
                    <textarea name="admin_note" id="decisionNote" rows="3" placeholder="Masukkan catatan keputusan..." class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-3 text-sm text-gray-800 placeholder:text-gray-500 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-gray-400 shadow-theme-xs"></textarea>
                </div>
                <div class="flex items-center justify-end gap-3">
                    <button type="button" onclick="closeDecisionModal()" class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 px-5 py-2.5 rounded-lg text-sm font-medium transition-colors dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600">
                        Batal
                    </button>
                    <button type="submit" id="decisionSubmitBtn" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg text-sm font-medium shadow-theme-xs transition-colors text-white bg-gray-400 cursor-not-allowed">
                        <span id="decisionSubmitText">Pilih Keputusan</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openDecisionModal(bookingId, bookingNumber, activityName) {
    var modal = document.getElementById('decisionModal');
    var form = document.getElementById('decisionForm');
    var btn = document.getElementById('decisionSubmitBtn');
    var text = document.getElementById('decisionSubmitText');
    var title = document.getElementById('decisionModalTitle');
    var desc = document.getElementById('decisionModalDesc');
    var note = document.getElementById('decisionNote');
    var indicator = document.getElementById('requiredIndicator');

    form.action = '/pengajuan/' + bookingId + '/decision';
    document.getElementById('decisionBookingNumber').textContent = bookingNumber;
    document.getElementById('decisionActivityName').textContent = activityName;
    document.getElementById('decisionAction').value = '';
    note.value = '';
    note.required = false;
    indicator.style.display = 'inline';
    btn.disabled = true;
    btn.className = 'inline-flex items-center gap-2 px-5 py-2.5 rounded-lg text-sm font-medium shadow-theme-xs transition-colors text-white bg-gray-400 cursor-not-allowed';
    text.textContent = 'Pilih Keputusan';
    title.textContent = 'Keputusan Pengajuan';
    desc.textContent = 'Pilih keputusan untuk pengajuan ini.';
    note.placeholder = 'Masukkan catatan keputusan...';
    modal.style.display = 'block';
}

function closeDecisionModal() {
    var modal = document.getElementById('decisionModal');
    modal.style.display = 'none';
}

function setDecision(action) {
    document.getElementById('decisionAction').value = action;
    resetDecisionButtons();
    var btn = document.getElementById('decisionSubmitBtn');
    var text = document.getElementById('decisionSubmitText');
    var title = document.getElementById('decisionModalTitle');
    var desc = document.getElementById('decisionModalDesc');
    var note = document.getElementById('decisionNote');
    var indicator = document.getElementById('requiredIndicator');

    var isRequired = (action !== 'approve');
    note.required = isRequired;
    indicator.style.display = isRequired ? 'inline' : 'none';

    btn.disabled = false;
    if (action === 'approve') {
        btn.className = 'inline-flex items-center gap-2 px-5 py-2.5 rounded-lg text-sm font-medium shadow-theme-xs transition-colors text-white bg-green-600 hover:bg-green-700 cursor-pointer';
        text.textContent = 'Setujui';
        title.textContent = 'Setujui Pengajuan';
        desc.textContent = 'Konfirmasi persetujuan pengajuan ini. Catatan bersifat opsional.';
        note.placeholder = 'Tambahkan catatan (opsional)...';
    } else if (action === 'reject') {
        btn.className = 'inline-flex items-center gap-2 px-5 py-2.5 rounded-lg text-sm font-medium shadow-theme-xs transition-colors text-white bg-red-600 hover:bg-red-700 cursor-pointer';
        text.textContent = 'Tolak';
        title.textContent = 'Tolak Pengajuan';
        desc.textContent = 'Tolak pengajuan ini. Anda wajib memberikan catatan alasan penolakan.';
        note.placeholder = 'Masukkan alasan penolakan...';
    } else if (action === 'revision') {
        btn.className = 'inline-flex items-center gap-2 px-5 py-2.5 rounded-lg text-sm font-medium shadow-theme-xs transition-colors text-white bg-orange-500 hover:bg-orange-600 cursor-pointer';
        text.textContent = 'Minta Revisi';
        title.textContent = 'Minta Revisi Pengajuan';
        desc.textContent = 'Minta pengaju melakukan revisi. Anda wajib memberikan catatan apa yang perlu diperbaiki.';
        note.placeholder = 'Masukkan catatan revisi...';
    }
    event.currentTarget.classList.add('ring-2', 'ring-offset-2', 'ring-brand-500');
}

function resetDecisionButtons() {
    document.querySelectorAll('.decision-action-btn').forEach(function(el) {
        el.classList.remove('ring-2', 'ring-offset-2', 'ring-brand-500');
    });
}
</script>
@endsection
