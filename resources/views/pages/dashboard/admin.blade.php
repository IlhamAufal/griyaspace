@extends('layouts.app')

@section('content')
<div class="space-y-6">

    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Dashboard Admin</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Ringkasan data, statistik visual, dan pemantauan pemesanan ruangan</p>
        </div>
        <div class="flex items-center gap-3">
            <div class="px-3.5 py-2 rounded-xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700/60 shadow-xs text-right">
                <p class="text-xs font-semibold text-gray-800 dark:text-gray-200 flex items-center gap-1.5 justify-end">
                    <i class="fa-regular fa-calendar text-[#2F3185] dark:text-brand-400"></i>
                    <span>{{ now()->translatedFormat('l, d F Y') }}</span>
                </p>
                <p class="text-[11px] text-gray-400 dark:text-gray-500 mt-0.5">Pembaruan: {{ now()->format('H:i') }} WIB</p>
            </div>
        </div>
    </div>

    <!-- Pending Bookings Alert Banner -->
    @if($pendingCount > 0)
    <a href="{{ route('bookings.konfirmasi') }}" class="flex items-center gap-4 p-4 rounded-xl bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-700/60 hover:shadow-md hover:border-amber-300 dark:hover:border-amber-600 transition-all group">
        <div class="w-10 h-10 rounded-xl bg-amber-100 dark:bg-amber-900/40 text-amber-600 dark:text-amber-400 flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
            <i class="fa-solid fa-bell text-lg"></i>
        </div>
        <div class="flex-1 min-w-0">
            <p class="text-sm font-semibold text-amber-800 dark:text-amber-200">
                Anda memiliki <span class="text-amber-600 dark:text-amber-300 font-bold">{{ $pendingCount }}</span> pengajuan untuk dikonfirmasi
            </p>
            <p class="text-xs text-amber-600/70 dark:text-amber-400/60 mt-0.5">Klik untuk menuju halaman konfirmasi</p>
        </div>
        <i class="fa-solid fa-arrow-right text-amber-400 dark:text-amber-500 group-hover:text-amber-600 dark:group-hover:text-amber-300 group-hover:translate-x-1 transition-all"></i>
    </a>
    @endif

    <!-- Stats Cards (Menggunakan Palette: Navy #2F3185, Kuning #FFB800, Toska #1CBDB3) -->
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <x-dashboard.stat-card
            title="Booking Disetujui"
            :value="$approvedCount"
            icon="fa-circle-check"
            color="navy"/>

        <x-dashboard.stat-card
            title="Pengajuan Menunggu"
            :value="$pendingCount"
            icon="fa-clock-rotate-left"
            color="kuning"/>

        <x-dashboard.stat-card
            title="Jadwal Hari Ini"
            :value="$todayBookings"
            icon="fa-calendar-day"
            color="toska"/>

        <x-dashboard.stat-card
            title="Ruangan Aktif"
            :value="$activeRooms"
            icon="fa-door-open"
            color="navy"/>
    </div>

    <!-- Charts & Statistics Section -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        <!-- Chart 1: Tren Pengajuan & Booking Bulanan (Area Chart) -->
        <div class="lg:col-span-7 bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700/60 p-5 sm:p-6 shadow-xs">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pb-4 mb-4 border-b border-gray-100 dark:border-gray-700/60">
                <div>
                    <h2 class="text-base font-bold text-gray-900 dark:text-white flex items-center gap-2">
                        <span>Tren Aktivitas Peminjaman Ruangan</span>
                    </h2>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Statistik perbandingan ajuan disetujui vs pengajuan masuk (6 Bulan Terakhir)</p>
                </div>
                <div class="flex items-center gap-4 text-xs font-semibold">
                    <span class="inline-flex items-center gap-1.5 text-[#2F3185] dark:text-brand-300">
                        <span class="w-3 h-3 rounded-md bg-[#2F3185]"></span>
                        <span>Disetujui</span>
                    </span>
                    <span class="inline-flex items-center gap-1.5 text-[#1CBDB3] dark:text-teal-400">
                        <span class="w-3 h-3 rounded-md bg-[#1CBDB3]"></span>
                        <span>Pengajuan</span>
                    </span>
                </div>
            </div>

            <div id="monthlyTrendChart" class="w-full min-h-[300px]"></div>
        </div>

        <!-- Chart 2: Distribusi Status & Ruangan Terpopuler -->
        <div class="lg:col-span-5 space-y-6">

            <!-- Donut Chart Distribusi Status -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700/60 p-5 sm:p-6 shadow-xs">
                <div class="flex items-center justify-between pb-3 mb-3 border-b border-gray-100 dark:border-gray-700/60">
                    <h2 class="text-base font-bold text-gray-900 dark:text-white flex items-center gap-2">
                        <span>Distribusi Status Pengajuan</span>
                    </h2>
                    {{-- <span class="text-xs font-semibold px-2 py-0.5 rounded-md bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                        Total: {{ $totalBookings }}
                    </span> --}}
                </div>

                <div id="statusDonutChart" class="w-full min-h-[220px] flex items-center justify-center"></div>

                <!-- Custom Legend Badges (High Contrast & Clear Readability - 2x2 Grid) -->
                <div class="grid grid-cols-2 gap-2.5 mt-4 pt-3.5 border-t border-gray-100 dark:border-gray-700/60 text-xs">
                    <!-- 1. Disetujui -->
                    <div class="flex items-center justify-between p-2.5 rounded-xl bg-brand-50/70 dark:bg-brand-950/40 border border-brand-200/80 dark:border-brand-800/60">
                        <div class="flex items-center gap-2 min-w-0">
                            <span class="w-2.5 h-2.5 rounded-full bg-[#2F3185] dark:bg-brand-400 shrink-0"></span>
                            <span class="font-medium text-gray-700 dark:text-gray-300 truncate">Disetujui</span>
                        </div>
                        <span class="font-extrabold text-sm text-[#2F3185] dark:text-brand-300">{{ $approvedCount }}</span>
                    </div>

                    <!-- 2. Menunggu -->
                    <div class="flex items-center justify-between p-2.5 rounded-xl bg-teal-50/70 dark:bg-teal-950/40 border border-teal-200/80 dark:border-teal-800/60">
                        <div class="flex items-center gap-2 min-w-0">
                            <span class="w-2.5 h-2.5 rounded-full bg-[#1CBDB3] dark:bg-teal-400 shrink-0"></span>
                            <span class="font-medium text-gray-700 dark:text-gray-300 truncate">Menunggu</span>
                        </div>
                        <span class="font-extrabold text-sm text-[#0D9488] dark:text-teal-300">{{ $pendingCount }}</span>
                    </div>

                    <!-- 3. Revisi -->
                    <div class="flex items-center justify-between p-2.5 rounded-xl bg-amber-50/70 dark:bg-amber-950/40 border border-amber-200/80 dark:border-amber-800/60">
                        <div class="flex items-center gap-2 min-w-0">
                            <span class="w-2.5 h-2.5 rounded-full bg-[#FFB800] dark:bg-amber-400 shrink-0"></span>
                            <span class="font-medium text-gray-700 dark:text-gray-300 truncate">Revisi</span>
                        </div>
                        <span class="font-extrabold text-sm text-amber-800 dark:text-amber-300">{{ $revisionCount }}</span>
                    </div>

                    <!-- 4. Ditolak -->
                    <div class="flex items-center justify-between p-2.5 rounded-xl bg-red-50/70 dark:bg-red-950/40 border border-red-200/80 dark:border-red-800/60">
                        <div class="flex items-center gap-2 min-w-0">
                            <span class="w-2.5 h-2.5 rounded-full bg-[#EF4444] dark:bg-red-400 shrink-0"></span>
                            <span class="font-medium text-gray-700 dark:text-gray-300 truncate">Ditolak</span>
                        </div>
                        <span class="font-extrabold text-sm text-red-700 dark:text-red-300">{{ $rejectedCount }}</span>
                    </div>
                </div>
            </div>

        </div>

    </div>

    <!-- Shortcuts -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <a href="{{ route('bookings.konfirmasi') }}" class="group flex items-center gap-4 rounded-2xl border border-gray-200 bg-white p-5 shadow-xs dark:border-gray-700 dark:bg-gray-800 hover:shadow-md hover:border-brand-300 dark:hover:border-brand-500 transition-all">
            <div class="w-12 h-12 rounded-xl bg-[#2F3185]/10 dark:bg-brand-500/20 text-[#2F3185] dark:text-brand-400 flex items-center justify-center group-hover:scale-110 transition-transform">
                <i class="fa-solid fa-clipboard-check text-xl"></i>
            </div>
            <div>
                <p class="text-sm font-bold text-gray-900 dark:text-white">Pemeriksaan Pengajuan</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Tinjau dan proses permohonan booking yang masuk</p>
            </div>
            <i class="fa-solid fa-arrow-right text-gray-300 dark:text-gray-600 ml-auto group-hover:text-brand-500 group-hover:translate-x-1 transition-all"></i>
        </a>
        <a href="{{ route('calendar.index') }}" class="group flex items-center gap-4 rounded-2xl border border-gray-200 bg-white p-5 shadow-xs dark:border-gray-700 dark:bg-gray-800 hover:shadow-md hover:border-brand-300 dark:hover:border-brand-500 transition-all">
            <div class="w-12 h-12 rounded-xl bg-[#1CBDB3]/10 dark:bg-accent-500/20 text-[#1CBDB3] dark:text-accent-400 flex items-center justify-center group-hover:scale-110 transition-transform">
                <i class="fa-solid fa-calendar-days text-xl"></i>
            </div>
            <div>
                <p class="text-sm font-bold text-gray-900 dark:text-white">Kalender Pemakaian</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Pantau seluruh jadwal dan okupansi ruangan</p>
            </div>
            <i class="fa-solid fa-arrow-right text-gray-300 dark:text-gray-600 ml-auto group-hover:text-brand-500 group-hover:translate-x-1 transition-all"></i>
        </a>
    </div>

    <!-- Tables Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- Pengajuan Terbaru -->
        <x-dashboard.section title="Pengajuan Terbaru" href="{{ route('bookings.konfirmasi') }}">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-600 dark:text-gray-300">
                    <thead class="bg-[#2F3185] text-white">
                        <tr>
                            <th class="px-4 py-3 text-xs font-bold uppercase tracking-wider">Organisasi</th>
                            <th class="px-4 py-3 text-xs font-bold uppercase tracking-wider">Ruangan</th>
                            <th class="px-4 py-3 text-xs font-bold uppercase tracking-wider">Tanggal</th>
                            <th class="px-4 py-3 text-xs font-bold uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($recentSubmissions as $submission)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">{{ $submission->organization->name ?? '-' }}</td>
                                <td class="px-4 py-3">{{ $submission->room->name ?? '-' }}</td>
                                <td class="px-4 py-3 text-gray-500 dark:text-gray-400">{{ \Carbon\Carbon::parse($submission->booking_date)->format('d M Y') }}</td>
                                <td class="px-4 py-3"><x-dashboard.status-badge :status="$submission->status" /></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-8 text-center text-gray-400">
                                    <i class="fa-regular fa-folder-open text-2xl mb-2 block"></i>
                                    Tidak ada pengajuan terbaru
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-dashboard.section>

        <!-- Booking Mendatang -->
        <x-dashboard.section title="Booking Mendatang" href="{{ route('calendar.index') }}">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-600 dark:text-gray-300">
                    <thead class="bg-[#2F3185] text-white">
                        <tr>
                            <th class="px-4 py-3 text-xs font-bold uppercase tracking-wider">Organisasi</th>
                            <th class="px-4 py-3 text-xs font-bold uppercase tracking-wider">Ruangan</th>
                            <th class="px-4 py-3 text-xs font-bold uppercase tracking-wider">Tanggal</th>
                            <th class="px-4 py-3 text-xs font-bold uppercase tracking-wider">Waktu</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($upcomingBookings as $booking)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">{{ $booking->organization->name ?? '-' }}</td>
                                <td class="px-4 py-3">{{ $booking->room->name ?? '-' }}</td>
                                <td class="px-4 py-3 text-gray-500 dark:text-gray-400">{{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}</td>
                                <td class="px-4 py-3">{{ substr($booking->start_time, 0, 5) }} - {{ substr($booking->end_time, 0, 5) }}</td>
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
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const isDark = document.documentElement.classList.contains('dark');
    const primaryNavy = '#2F3185';
    const secondaryYellow = '#FFB800';
    const thirdToska = '#1CBDB3';
    const grayNeutral = '#94A3B8';

    // 1. Monthly Trend Area Chart
    const trendOptions = {
        series: [
            {
                name: 'Pengajuan Disetujui',
                data: @json($monthlyApproved)
            },
            {
                name: 'Pengajuan Masuk',
                data: @json($monthlySubmitted)
            }
        ],
        chart: {
            type: 'area',
            height: 310,
            fontFamily: 'Poppins, sans-serif',
            toolbar: { 
                show: true,
                tools: {
                    download: false,
                    selection: true,
                    zoom: true,
                    zoomin: true,
                    zoomout: true,
                    pan: true,
                    reset: true
                }
            },
            zoom: { enabled: true }
        },
        colors: [primaryNavy, thirdToska],
        dataLabels: { enabled: false },
        stroke: {
            curve: 'smooth',
            width: [3, 2],
            dashArray: [0, 4]
        },
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.4,
                opacityTo: 0.05,
                stops: [0, 90, 100]
            }
        },
        xaxis: {
            categories: @json($monthlyLabels),
            labels: {
                style: {
                    colors: isDark ? '#9CA3AF' : '#6B7280',
                    fontSize: '12px'
                }
            },
            axisBorder: { show: false },
            axisTicks: { show: false }
        },
        yaxis: {
            labels: {
                style: {
                    colors: isDark ? '#9CA3AF' : '#6B7280',
                    fontSize: '12px'
                },
                formatter: function (val) {
                    return Math.round(val);
                }
            }
        },
        grid: {
            borderColor: isDark ? '#374151' : '#F3F4F6',
            strokeDashArray: 4
        },
        legend: { show: false },
        tooltip: {
            theme: isDark ? 'dark' : 'light',
            y: {
                formatter: function (val) {
                    return val + ' Peminjaman';
                }
            }
        }
    };

    const trendChartEl = document.querySelector("#monthlyTrendChart");
    if (trendChartEl && window.ApexCharts) {
        const trendChart = new ApexCharts(trendChartEl, trendOptions);
        trendChart.render();
    }

    // 2. Status Donut Chart
    const statusOptions = {
        series: @json($statusData['series']),
        labels: @json($statusData['labels']),
        chart: {
            type: 'donut',
            height: 220,
            fontFamily: 'Poppins, sans-serif'
        },
        colors: [primaryNavy, thirdToska, secondaryYellow, '#EF4444'],
        plotOptions: {
            pie: {
                donut: {
                    size: '72%',
                    labels: {
                        show: true,
                        name: {
                            show: true,
                            fontSize: '12px',
                            fontWeight: 600,
                            color: isDark ? '#E5E7EB' : '#4B5563',
                            offsetY: -4
                        },
                        value: {
                            show: true,
                            fontSize: '20px',
                            fontWeight: 700,
                            color: isDark ? '#FFFFFF' : '#111827',
                            offsetY: 4,
                            formatter: function (val) {
                                return val;
                            }
                        },
                        total: {
                            show: true,
                            label: 'Total',
                            fontSize: '12px',
                            fontWeight: 600,
                            color: isDark ? '#9CA3AF' : '#6B7280',
                            formatter: function (w) {
                                return w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                            }
                        }
                    }
                }
            }
        },
        dataLabels: { enabled: false },
        legend: { show: false },
        tooltip: {
            theme: isDark ? 'dark' : 'light',
            style: {
                fontSize: '12px',
                fontFamily: 'Poppins, sans-serif'
            },
            y: {
                formatter: function (val) {
                    return val + ' Berkas';
                }
            }
        },
        stroke: {
            colors: isDark ? ['#1F2937'] : ['#FFFFFF'],
            width: 2
        }
    };

    const statusChartEl = document.querySelector("#statusDonutChart");
    if (statusChartEl && window.ApexCharts) {
        const statusChart = new ApexCharts(statusChartEl, statusOptions);
        statusChart.render();
    }
});
</script>
@endpush

