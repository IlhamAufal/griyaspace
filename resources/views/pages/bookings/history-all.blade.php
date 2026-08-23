@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="flex items-center gap-2 text-sm text-gray-500 mb-6">
            <a href="{{ route('dashboard') }}" class="hover:text-blue-600 transition-colors"><i class="fa-solid fa-house"></i></a>
            <i class="fa-solid fa-chevron-right text-xs"></i>
            <span class="text-gray-900 font-medium">Riwayat Pengajuan</span>
        </nav>

        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Riwayat Pengajuan</h1>
            <a href="{{ route('bookings.create') }}" class="bg-accent-500 hover:bg-accent-600 text-white px-4 py-2 rounded-lg inline-flex items-center text-sm font-medium shadow-theme-xs transition-colors">
                <span>Pengajuan Baru</span>
            </a>
        </div>

        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-200 dark:border-gray-700/60 p-4 mb-5">
            <form action="{{ route('bookings.historyAll') }}" method="GET" class="flex flex-wrap items-end gap-4">
                <div class="flex-1 min-w-[220px]">
                    <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">Pencarian</label>
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="No. booking, nama kegiatan..." class="h-10 w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm bg-transparent placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 shadow-theme-xs">
                        <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                    </div>
                </div>
                @if(auth()->user()->isAdmin() && $users->count())
                <div class="w-56">
                    <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">Pengguna</label>
                    <select name="user_id" class="h-10 w-full border border-gray-300 rounded-lg text-sm px-3 py-2 bg-transparent focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 shadow-theme-xs">
                        <option value="">Semua Pengguna</option>
                        @foreach($users as $u)
                        <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                        @endforeach
                    </select>
                </div>
                @endif
                <div class="w-44">
                    <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">Status</label>
                    <select name="status" class="h-10 w-full border border-gray-300 rounded-lg text-sm px-3 py-2 bg-transparent focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 shadow-theme-xs">
                        <option value="">Semua Status</option>
                        <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Disetujui</option>
                        <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Ditolak</option>
                        <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="h-10 bg-secondary-500 hover:bg-secondary-600 text-white px-4 rounded-lg text-sm font-medium inline-flex items-center gap-2 shadow-theme-xs transition-colors">
                        <i class="fa-solid fa-filter text-xs"></i> Filter
                    </button>
                    @if(request()->hasAny(['search', 'user_id', 'status']))
                    <a href="{{ route('bookings.historyAll') }}" class="h-10 bg-gray-100 hover:bg-gray-200 text-gray-700 dark:bg-gray-700 dark:text-gray-200 px-4 rounded-lg text-sm font-medium inline-flex items-center gap-2 transition-colors">
                        <i class="fa-solid fa-xmark text-xs"></i> Reset
                    </a>
                    @endif
                </div>
            </form>
        </div>

        <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden"
             x-data="{ tableLoaded: false }"
             x-init="tableLoaded = true">

            <div x-show="!tableLoaded" class="p-6">
                <x-skeleton.table :rows="5" :cols="auth()->user()->isAdmin() ? 7 : 6" :showHeader="false" />
            </div>

            <div x-show="tableLoaded" class="overflow-x-auto transition-opacity duration-300">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-brand-500 text-white">
                    <tr>
                        {{-- <th class="px-6 py-3.5 text-left text-xs font-bold text-white uppercase tracking-wider">No</th> --}}
                        <th class="px-6 py-3.5 text-left text-xs font-bold text-white uppercase tracking-wider">No. Booking</th>
                        @if(auth()->user()->isAdmin())
                        <th class="px-6 py-3.5 text-left text-xs font-bold text-white uppercase tracking-wider">Pengaju</th>
                        @endif
                        <th class="px-6 py-3.5 text-left text-xs font-bold text-white uppercase tracking-wider">Ruangan</th>
                        <th class="px-6 py-3.5 text-left text-xs font-bold text-white uppercase tracking-wider">Kegiatan</th>
                        <th class="px-6 py-3.5 text-left text-xs font-bold text-white uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3.5 text-left text-xs font-bold text-white uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3.5 text-left text-xs font-bold text-white uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($bookings as $booking)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $booking->booking_number }}</td>
                        @if(auth()->user()->isAdmin())
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $booking->submittedBy->name ?? '-' }}</td>
                        @endif
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $booking->room->name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $booking->activity_name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $booking->booking_date->format('d/m/Y') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $booking->status->colorClasses() }}">
                                {{ $booking->status->label() }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('bookings.show', $booking) }}" class="inline-flex items-center justify-center w-8 h-8 text-blue-600 hover:text-blue-900 hover:bg-blue-50 rounded-lg transition-colors dark:text-blue-400 dark:hover:bg-blue-900/20" title="Lihat Detail">
                                    <i class="fa-solid fa-eye text-sm"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ auth()->user()->isAdmin() ? 6 : 5 }}" class="px-6 py-4 text-center text-sm text-gray-500">Tidak ada riwayat pengajuan</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $bookings->links() }}
        </div>
    </div>
</div>
@endsection
