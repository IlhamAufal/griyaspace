@extends('layouts.app')

@section('content')
<div class="space-y-6">

    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Dashboard</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Selamat datang, {{ auth()->user()->name }}</p>
        </div>
        <div class="text-right">
            <p class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ now()->translatedFormat('l, d F Y') }}</p>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
        <x-dashboard.stat-card title="Pengajuan Menunggu" :value="$myPendingCount" icon="fa-clock-rotate-left" color="yellow" />
        <x-dashboard.stat-card title="Booking Disetujui" :value="$myApprovedCount" icon="fa-circle-check" color="green" />
        <x-dashboard.stat-card title="Perlu Revisi / Ditolak" :value="$myRevisionRejectedCount" icon="fa-pen-to-square" color="orange" />
    </div>

    <!-- Shortcuts -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <a href="{{ route('bookings.create') }}" class="group flex items-center gap-4 rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800 hover:shadow-md hover:border-brand-300 dark:hover:border-brand-500 transition-all">
            <div class="w-10 h-10 rounded-lg bg-brand-50 dark:bg-brand-500/10 flex items-center justify-center group-hover:scale-110 transition-transform">
                <i class="fa-solid fa-plus text-brand-500"></i>
            </div>
            <div>
                <p class="text-sm font-semibold text-gray-900 dark:text-white">Ajukan Pengajuan Baru</p>
                <p class="text-xs text-gray-500 dark:text-gray-400">Buat pengajuan pemesanan ruangan</p>
            </div>
            <i class="fa-solid fa-arrow-right text-gray-300 dark:text-gray-600 ml-auto group-hover:text-brand-500 transition-colors"></i>
        </a>
        <a href="{{ route('bookings.index') }}" class="group flex items-center gap-4 rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800 hover:shadow-md hover:border-brand-300 dark:hover:border-brand-500 transition-all">
            <div class="w-10 h-10 rounded-lg bg-brand-50 dark:bg-brand-500/10 flex items-center justify-center group-hover:scale-110 transition-transform">
                <i class="fa-solid fa-file-invoice text-brand-500"></i>
            </div>
            <div>
                <p class="text-sm font-semibold text-gray-900 dark:text-white">Daftar Surat Izin</p>
                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $validPermitsCount }} surat izin aktif</p>
            </div>
            <i class="fa-solid fa-arrow-right text-gray-300 dark:text-gray-600 ml-auto group-hover:text-brand-500 transition-colors"></i>
        </a>
    </div>

    <!-- Table -->
    <x-dashboard.section title="Booking Terdekat" :count="$nearestBookings->count()" dot-color="green" href="{{ route('bookings.index') }}">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600 dark:text-gray-300">
                <thead class="bg-brand-500 text-white">
                    <tr>
                        <th class="px-4 py-3 text-xs font-bold uppercase tracking-wider">Ruangan</th>
                        <th class="px-4 py-3 text-xs font-bold uppercase tracking-wider">Tanggal</th>
                        <th class="px-4 py-3 text-xs font-bold uppercase tracking-wider">Waktu</th>
                        <th class="px-4 py-3 text-xs font-bold uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($nearestBookings as $booking)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">{{ $booking->room->name ?? '-' }}</td>
                            <td class="px-4 py-3 text-gray-500 dark:text-gray-400">{{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}</td>
                            <td class="px-4 py-3">{{ substr($booking->start_time, 0, 5) }} - {{ substr($booking->end_time, 0, 5) }}</td>
                            <td class="px-4 py-3"><x-dashboard.status-badge :status="$booking->status" /></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-8 text-center text-gray-400">
                                <i class="fa-regular fa-calendar-xmark text-2xl mb-2 block"></i>
                                Tidak ada booking mendatang
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-dashboard.section>

</div>
@endsection
