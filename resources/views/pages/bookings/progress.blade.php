@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="flex items-center gap-2 text-sm text-gray-500 mb-6">
            <a href="{{ route('dashboard') }}" class="hover:text-brand-600 transition-colors"><i class="fa-solid fa-house"></i></a>
            <i class="fa-solid fa-chevron-right text-xs"></i>
            <span class="text-gray-900 font-medium">Progress Pengajuan</span>
        </nav>

        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Progress Pengajuan</h1>
        </div>

        <div x-data="{
            search: '{{ request('search') }}',
            filters: { status: '{{ request('status') }}' },
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
                this.filters = { status: '' };
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
                if (this.sort) params.set('sort', this.sort);
                if (this.direction) params.set('direction', this.direction);
                params.set('page', p);
                const url = '{{ route('bookings.progress') }}?' + params.toString();
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
                            <input type="text" x-model="search" @input.debounce.400ms="doSearch()" placeholder="No. booking, nama kegiatan..." class="h-10 w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm bg-transparent placeholder:text-gray-500 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 shadow-theme-xs">
                            <i x-show="!loading" class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-500 dark:text-gray-400 text-xs"></i>
                            <i x-show="loading" x-cloak class="fa-solid fa-spinner fa-spin absolute left-3.5 top-1/2 -translate-y-1/2 text-brand-500 text-xs"></i>
                        </div>
                    </div>
                    <div class="w-48">
                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">Status</label>
                        <x-common.select x-model="filters.status" @change="applyFilter()" class="h-10 w-full border border-gray-300 rounded-lg text-sm px-3 py-2 bg-transparent focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 shadow-theme-xs">
                            <option value="">Semua Status</option>
                            <option value="submitted">Diajukan</option>
                            <option value="revision">Revisi</option>
                            <option value="approved">Disetujui</option>
                            <option value="rejected">Ditolak</option>
                            <option value="cancelled">Dibatalkan</option>
                        </x-common.select>
                    </div>
                    <button type="button" @click="clearFilters()" x-show="search || filters.status" x-cloak class="h-10 bg-gray-100 hover:bg-gray-200 text-gray-700 dark:bg-gray-700 dark:text-gray-200 px-4 rounded-lg text-sm font-medium inline-flex items-center gap-2 transition-colors">
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
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">{{ $booking->booking_number }}</td>
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
                                            <a href="{{ route('bookings.show', $booking) }}" class="inline-flex items-center justify-center w-8 h-8 text-brand-600 hover:text-brand-900 hover:bg-brand-50 dark:hover:bg-brand-900/30 rounded-lg transition-colors" title="Lihat Detail">
                                                <i class="fa-solid fa-eye text-sm"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-8 text-center text-sm text-gray-500 dark:text-gray-400">Tidak ada data pengajuan</td>
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
@endsection
