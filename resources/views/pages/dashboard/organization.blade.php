@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Dashboard</h1>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Menunggu</p>
            <p class="mt-2 text-3xl font-bold text-yellow-500">{{ $myPendingCount }}</p>
        </div>
        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Disetujui</p>
            <p class="mt-2 text-3xl font-bold text-green-500">{{ $myApprovedCount }}</p>
        </div>
        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Perlu Revisi</p>
            <p class="mt-2 text-3xl font-bold text-orange-500">{{ $myRevisionCount }}</p>
        </div>
    </div>

    <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
        <div class="border-b border-gray-200 px-5 py-4 dark:border-gray-700">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Booking Mendatang</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600 dark:text-gray-300">
                <thead class="bg-brand-500 text-white">
                    <tr>
                        <th class="px-5 py-3 text-xs font-bold text-white uppercase tracking-wider">Ruangan</th>
                        <th class="px-5 py-3 text-xs font-bold text-white uppercase tracking-wider">Tanggal</th>
                        <th class="px-5 py-3 text-xs font-bold text-white uppercase tracking-wider">Waktu</th>
                        <th class="px-5 py-3 text-xs font-bold text-white uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($upcomingBookings as $booking)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                            <td class="px-5 py-3">{{ $booking->room->name ?? '-' }}</td>
                            <td class="px-5 py-3">{{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}</td>
                            <td class="px-5 py-3">{{ $booking->start_time }} - {{ $booking->end_time }}</td>
                            <td class="px-5 py-3">
                                <span class="inline-block rounded-full px-2 py-0.5 text-xs font-medium
                                    {{ $booking->status === 'approved' ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : '' }}
                                    {{ $booking->status === 'pending' ? 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400' : '' }}
                                    {{ $booking->status === 'revision' ? 'bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400' : '' }}">
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-5 py-8 text-center text-gray-400">Tidak ada booking mendatang</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
        <div class="border-b border-gray-200 px-5 py-4 dark:border-gray-700">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Pengajuan Terakhir</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600 dark:text-gray-300">
                <thead class="bg-brand-500 text-white">
                    <tr>
                        <th class="px-5 py-3 text-xs font-bold text-white uppercase tracking-wider">Ruangan</th>
                        <th class="px-5 py-3 text-xs font-bold text-white uppercase tracking-wider">Tanggal</th>
                        <th class="px-5 py-3 text-xs font-bold text-white uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($recentBookings as $booking)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                            <td class="px-5 py-3">{{ $booking->room->name ?? '-' }}</td>
                            <td class="px-5 py-3">{{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}</td>
                            <td class="px-5 py-3">
                                <span class="inline-block rounded-full px-2 py-0.5 text-xs font-medium
                                    {{ $booking->status === 'approved' ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : '' }}
                                    {{ $booking->status === 'pending' ? 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400' : '' }}
                                    {{ $booking->status === 'rejected' ? 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' : '' }}
                                    {{ $booking->status === 'revision' ? 'bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400' : '' }}">
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-5 py-8 text-center text-gray-400">Tidak ada pengajuan terakhir</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
