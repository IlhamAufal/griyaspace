@extends('layouts.app')

@section('content')
<div class="space-y-6">

    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Dashboard Organisasi</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Selamat datang, <strong class="text-gray-800 dark:text-gray-200">{{ auth()->user()->name }}</strong> ({{ auth()->user()->organization->name ?? 'Organisasi' }})</p>
        </div>
        <div class="flex items-center gap-3">
            <div class="px-3.5 py-2 rounded-xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700/60 shadow-xs text-right">
                <p class="text-xs font-semibold text-gray-800 dark:text-gray-200 flex items-center gap-1.5 justify-end">
                    <i class="fa-regular fa-calendar text-[#2F3185] dark:text-brand-400"></i>
                    <span>{{ now()->translatedFormat('l, d F Y') }}</span>
                </p>
            </div>
        </div>
    </div>

    <!-- Stats Cards (Menggunakan Palette: Navy #2F3185, Kuning #FFB800, Toska #1CBDB3) -->
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <x-dashboard.stat-card
            title="Booking Disetujui"
            :value="$myApprovedCount"
            icon="fa-circle-check"
            color="navy"/>

        <x-dashboard.stat-card
            title="Pengajuan Menunggu"
            :value="$myPendingCount"
            icon="fa-clock-rotate-left"
            color="kuning"/>

        <x-dashboard.stat-card
            title="Surat Izin Aktif"
            :value="$validPermitsCount"
            icon="fa-file-invoice"
            color="toska"/>

        <x-dashboard.stat-card
            title="Perlu Revisi / Ditolak"
            :value="$myRevisionRejectedCount"
            icon="fa-triangle-exclamation"
            color="kuning"/>
    </div>

    <!-- Charts & Statistics Section -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        <!-- Chart 1: Riwayat Aktivitas Peminjaman Organisasi (Area Chart) -->
        <div class="lg:col-span-7 bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700/60 p-5 sm:p-6 shadow-xs">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pb-4 mb-4 border-b border-gray-100 dark:border-gray-700/60">
                <div>
                    <h2 class="text-base font-bold text-gray-900 dark:text-white flex items-center gap-2">
                        <span>Riwayat Peminjaman Ruangan</span>
                    </h2>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Statistik pengajuan dan persetujuan ruangan organisasi Anda (6 Bulan Terakhir)</p>
                </div>
                <div class="flex items-center gap-4 text-xs font-semibold">
                    <span class="inline-flex items-center gap-1.5 text-[#2F3185] dark:text-brand-300">
                        <span class="w-3 h-3 rounded-md bg-[#2F3185]"></span>
                        <span>Disetujui</span>
                    </span>
                    <span class="inline-flex items-center gap-1.5 text-[#1CBDB3] dark:text-teal-400">
                        <span class="w-3 h-3 rounded-md bg-[#1CBDB3]"></span>
                        <span>Diajukan</span>
                    </span>
                </div>
            </div>

            <!-- ApexChart Container with Skeleton Fallback -->
            <div id="orgMonthlyChart" class="w-full min-h-[290px] overflow-hidden">
                <x-skeleton.chart height="290px" type="area" />
            </div>
        </div>

        <!-- Chart 2: Status Distribusi Pengajuan Organisasi (Donut Chart) -->
        <div class="lg:col-span-5 bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700/60 p-5 sm:p-6 shadow-xs flex flex-col justify-between">
            <div class="flex items-center justify-between pb-3 mb-3 border-b border-gray-100 dark:border-gray-700/60">
                <h2 class="text-base font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    <span>Status Pengajuan Saya</span>
                </h2>
                <span class="text-xs font-semibold px-2 py-0.5 rounded-md bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                    Total: {{ $totalMyBookings }}
                </span>
            </div>

            <div id="orgStatusDonutChart" class="w-full min-h-[220px] flex items-center justify-center">
                <x-skeleton.chart height="220px" type="donut" />
            </div>

            <!-- Custom Legend Badges (High Contrast & Clear Readability - 2x2 Grid) -->
            <div class="grid grid-cols-2 gap-2.5 mt-4 pt-3.5 border-t border-gray-100 dark:border-gray-700/60 text-xs">
                <div class="flex items-center justify-between p-2.5 rounded-xl bg-brand-50/70 dark:bg-brand-950/40 border border-brand-200/80 dark:border-brand-800/60">
                    <div class="flex items-center gap-2 min-w-0">
                        <span class="w-2.5 h-2.5 rounded-full bg-[#2F3185] dark:bg-brand-400 shrink-0"></span>
                        <span class="font-medium text-gray-700 dark:text-gray-300 truncate">Disetujui</span>
                    </div>
                    <span class="font-extrabold text-sm text-[#2F3185] dark:text-brand-300">{{ $myApprovedCount }}</span>
                </div>
                <div class="flex items-center justify-between p-2.5 rounded-xl bg-amber-50/70 dark:bg-amber-950/40 border border-amber-200/80 dark:border-amber-800/60">
                    <div class="flex items-center gap-2 min-w-0">
                        <span class="w-2.5 h-2.5 rounded-full bg-[#FFB800] dark:bg-amber-400 shrink-0"></span>
                        <span class="font-medium text-gray-700 dark:text-gray-300 truncate">Menunggu</span>
                    </div>
                    <span class="font-extrabold text-sm text-amber-800 dark:text-amber-300">{{ $myPendingCount }}</span>
                </div>
            </div>
        </div>

    </div>

    <!-- Shortcuts -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <a href="{{ route('bookings.create') }}" class="group flex items-center gap-4 rounded-2xl border border-gray-200 bg-white p-5 shadow-xs dark:border-gray-700 dark:bg-gray-800 hover:shadow-md hover:border-brand-300 dark:hover:border-brand-500 transition-all">
            <div class="w-12 h-12 rounded-xl bg-[#2F3185]/10 dark:bg-brand-500/20 text-[#2F3185] dark:text-brand-400 flex items-center justify-center group-hover:scale-110 transition-transform">
                <i class="fa-solid fa-plus text-xl"></i>
            </div>
            <div>
                <p class="text-sm font-bold text-gray-900 dark:text-white">Ajukan Peminjaman Baru</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Buat permohonan peminjaman ruangan baru</p>
            </div>
            <i class="fa-solid fa-arrow-right text-gray-300 dark:text-gray-600 ml-auto group-hover:text-brand-500 group-hover:translate-x-1 transition-all"></i>
        </a>
        <a href="{{ route('bookings.index') }}" class="group flex items-center gap-4 rounded-2xl border border-gray-200 bg-white p-5 shadow-xs dark:border-gray-700 dark:bg-gray-800 hover:shadow-md hover:border-brand-300 dark:hover:border-brand-500 transition-all">
            <div class="w-12 h-12 rounded-xl bg-[#1CBDB3]/10 dark:bg-accent-500/20 text-[#1CBDB3] dark:text-accent-400 flex items-center justify-center group-hover:scale-110 transition-transform">
                <i class="fa-solid fa-file-invoice text-xl"></i>
            </div>
            <div>
                <p class="text-sm font-bold text-gray-900 dark:text-white">Daftar Surat Izin & Berkas</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ $validPermitsCount }} surat izin pemakaian aktif</p>
            </div>
            <i class="fa-solid fa-arrow-right text-gray-300 dark:text-gray-600 ml-auto group-hover:text-brand-500 group-hover:translate-x-1 transition-all"></i>
        </a>
    </div>

    <!-- Table: Agenda Terdekat -->
    <x-dashboard.section title="Agenda Peminjaman Terdekat" href="{{ route('bookings.index') }}">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600 dark:text-gray-300">
                <thead class="bg-[#2F3185] text-white">
                    <tr>
                        <th class="px-4 py-3 text-xs font-bold uppercase tracking-wider">Kegiatan</th>
                        <th class="px-4 py-3 text-xs font-bold uppercase tracking-wider">Ruangan</th>
                        <th class="px-4 py-3 text-xs font-bold uppercase tracking-wider">Tanggal</th>
                        <th class="px-4 py-3 text-xs font-bold uppercase tracking-wider">Waktu</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($nearestBookings as $booking)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">{{ $booking->activity_name ?? '-' }}</td>
                            <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">{{ $booking->room->name ?? '-' }}</td>
                            <td class="px-4 py-3 text-gray-500 dark:text-gray-400">{{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}</td>
                            <td class="px-4 py-3">{{ substr($booking->start_time, 0, 5) }} - {{ substr($booking->end_time, 0, 5) }} WIB</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-8 text-center text-gray-400">
                                <i class="fa-regular fa-calendar-xmark text-2xl mb-2 block"></i>
                                Tidak ada jadwal peminjaman mendatang
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-dashboard.section>

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

    // 1. Monthly Org Activity Area Chart
    const orgTrendOptions = {
        series: [
            {
                name: 'Disetujui',
                data: @json($monthlyApproved)
            },
            {
                name: 'Diajukan',
                data: @json($monthlySubmitted)
            }
        ],
        chart: {
            type: 'area',
            height: 290,
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
                opacityFrom: [0.45, 0.25],
                opacityTo: [0.05, 0.02],
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
                    return val + ' Kegiatan';
                }
            }
        }
    };

    const orgTrendChartEl = document.querySelector("#orgMonthlyChart");
    if (orgTrendChartEl && window.ApexCharts) {
        const orgTrendChart = new ApexCharts(orgTrendChartEl, orgTrendOptions);
        orgTrendChart.render();
    }

    // 2. Org Status Donut Chart
    const orgStatusOptions = {
        series: @json($statusData['series']),
        labels: @json($statusData['labels']),
        chart: {
            type: 'donut',
            height: 220,
            fontFamily: 'Poppins, sans-serif'
        },
        colors: [primaryNavy, secondaryYellow, thirdToska, grayNeutral],
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
                    return val + ' Pengajuan';
                }
            }
        },
        stroke: {
            colors: isDark ? ['#1F2937'] : ['#FFFFFF'],
            width: 2
        }
    };

    const orgStatusChartEl = document.querySelector("#orgStatusDonutChart");
    if (orgStatusChartEl && window.ApexCharts) {
        const orgStatusChart = new ApexCharts(orgStatusChartEl, orgStatusOptions);
        orgStatusChart.render();
    }
});
</script>
@endpush

