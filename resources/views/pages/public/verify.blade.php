@extends('layouts.fullscreen-right-layout')

@php
    $title = 'Verifikasi Izin';
@endphp

@section('content')
<div>
    <div class="mb-6 text-center">
        <h1 class="text-title-sm sm:text-title-md mb-2 font-semibold text-gray-800 dark:text-white/90">
            Verifikasi Izin
        </h1>
        <p class="text-sm text-gray-500 dark:text-gray-400">
            Informasi validitas surat izin peminjaman ruangan Griya Mahasiswa
        </p>
    </div>

    @php
        $statusConfig = [
            'valid' => [
                'badge_bg' => 'bg-success-50 dark:bg-success-900/20',
                'badge_border' => 'border-success-200 dark:border-success-800/40',
                'badge_text' => 'text-success-700 dark:text-success-400',
                'icon_bg' => 'bg-success-500',
                'icon' => 'fa-solid fa-check',
                'label' => 'Izin Valid',
                'desc' => 'Surat izin peminjaman terdaftar resmi dan sah dalam sistem.'
            ],
            'active' => [
                'badge_bg' => 'bg-brand-50 dark:bg-brand-900/20',
                'badge_border' => 'border-brand-200 dark:border-brand-800/40',
                'badge_text' => 'text-brand-700 dark:text-brand-200',
                'icon_bg' => 'bg-brand-500',
                'icon' => 'fa-solid fa-check-double',
                'label' => 'Sedang Berlangsung',
                'desc' => 'Kegiatan sedang berlangsung hari ini sesuai jadwal izin.'
            ],
            'upcoming' => [
                'badge_bg' => 'bg-warning-50 dark:bg-warning-900/20',
                'badge_border' => 'border-warning-200 dark:border-warning-800/40',
                'badge_text' => 'text-warning-700 dark:text-warning-400',
                'icon_bg' => 'bg-warning-500',
                'icon' => 'fa-solid fa-clock',
                'label' => 'Akan Datang',
                'desc' => 'Izin peminjaman sah untuk kegiatan yang akan datang.'
            ],
            'expired' => [
                'badge_bg' => 'bg-error-50 dark:bg-error-900/20',
                'badge_border' => 'border-error-200 dark:border-error-800/40',
                'badge_text' => 'text-error-700 dark:text-error-400',
                'icon_bg' => 'bg-error-500',
                'icon' => 'fa-solid fa-calendar-xmark',
                'label' => 'Kadaluarsa',
                'desc' => 'Masa berlaku izin peminjaman telah berakhir.'
            ],
            'revoked' => [
                'badge_bg' => 'bg-gray-100 dark:bg-gray-800',
                'badge_border' => 'border-gray-200 dark:border-gray-700',
                'badge_text' => 'text-gray-700 dark:text-gray-300',
                'icon_bg' => 'bg-gray-500',
                'icon' => 'fa-solid fa-ban',
                'label' => 'Dicabut',
                'desc' => 'Izin peminjaman telah dibatalkan atau dicabut.'
            ],
            'not_found' => [
                'badge_bg' => 'bg-error-50 dark:bg-error-900/20',
                'badge_border' => 'border-error-200 dark:border-error-800/40',
                'badge_text' => 'text-error-700 dark:text-error-400',
                'icon_bg' => 'bg-error-500',
                'icon' => 'fa-solid fa-circle-xmark',
                'label' => 'Tidak Ditemukan',
                'desc' => 'Token verifikasi tidak valid atau data izin tidak terdaftar.'
            ],
        ];
        $config = $statusConfig[$status] ?? $statusConfig['not_found'];
    @endphp

    <!-- Status Banner Card -->
    <div class="rounded-xl border {{ $config['badge_border'] }} {{ $config['badge_bg'] }} p-4 sm:p-5 mb-5 transition-all">
        <div class="flex items-center gap-4">
            <span class="flex-shrink-0 w-12 h-12 {{ $config['icon_bg'] }} rounded-full flex items-center justify-center text-white shadow-sm">
                <i class="{{ $config['icon'] }} text-xl"></i>
            </span>
            <div>
                <h3 class="text-base font-semibold {{ $config['badge_text'] }}">{{ $config['label'] }}</h3>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ $config['desc'] }}</p>
            </div>
        </div>
    </div>

    @if(in_array($status, ['valid', 'active', 'upcoming', 'expired', 'revoked']) && $permit)
    <!-- Permit Details Card (Gaya Spesifikasi & Informasi Ruangan) -->
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-2xl border border-gray-200 dark:border-gray-700/60 p-5 sm:p-6 mb-6">
        <h2 class="text-base sm:text-lg font-bold text-gray-900 dark:text-white mb-5 flex items-center gap-2.5">
            <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-brand-50 dark:bg-brand-500/10 text-brand-500">
                <i class="fa-solid fa-file-circle-check text-sm"></i>
            </span>
            <span>Detail Surat Peminjaman Ruangan</span>
        </h2>

        <!-- Grid Items -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
            @if(!empty($permit->permit_number))
            <!-- Nomor Izin -->
            <div class="p-3.5 sm:p-4 rounded-xl bg-gray-50 dark:bg-gray-900/50 border border-gray-100 dark:border-gray-700/50 flex items-center gap-3.5 sm:col-span-2">
                <span class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-brand-50 dark:bg-brand-500/10 text-brand-500 shrink-0">
                    <i class="fa-solid fa-barcode text-sm"></i>
                </span>
                <div class="min-w-0">
                    <p class="text-xs font-semibold text-gray-800 dark:text-gray-200">Nomor Izin</p>
                    <p class="text-sm font-normal text-gray-600 dark:text-gray-400 truncate font-mono">{{ $permit->permit_number }}</p>
                </div>
            </div>
            @endif

            <!-- Kegiatan -->
            <div class="p-3.5 sm:p-4 rounded-xl bg-gray-50 dark:bg-gray-900/50 border border-gray-100 dark:border-gray-700/50 flex items-center gap-3.5 sm:col-span-2">
                <span class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-brand-50 dark:bg-brand-500/10 text-brand-500 shrink-0">
                    <i class="fa-solid fa-bullhorn text-sm"></i>
                </span>
                <div class="min-w-0">
                    <p class="text-xs font-semibold text-gray-800 dark:text-gray-200">Kegiatan</p>
                    <p class="text-sm font-normal text-gray-600 dark:text-gray-400">{{ $permit->booking->activity_name }}</p>
                </div>
            </div>

            <!-- Ruangan -->
            <div class="p-3.5 sm:p-4 rounded-xl bg-gray-50 dark:bg-gray-900/50 border border-gray-100 dark:border-gray-700/50 flex items-center gap-3.5">
                <span class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-brand-50 dark:bg-brand-500/10 text-brand-500 shrink-0">
                    <i class="fa-solid fa-door-open text-sm"></i>
                </span>
                <div class="min-w-0">
                    <p class="text-xs font-semibold text-gray-800 dark:text-gray-200">Ruangan</p>
                    <p class="text-sm font-normal text-gray-600 dark:text-gray-400 truncate">{{ $permit->booking->room->name }}</p>
                </div>
            </div>

            <!-- Organisasi -->
            <div class="p-3.5 sm:p-4 rounded-xl bg-gray-50 dark:bg-gray-900/50 border border-gray-100 dark:border-gray-700/50 flex items-center gap-3.5">
                <span class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-brand-50 dark:bg-brand-500/10 text-brand-500 shrink-0">
                    <i class="fa-solid fa-sitemap text-sm"></i>
                </span>
                <div class="min-w-0">
                    <p class="text-xs font-semibold text-gray-800 dark:text-gray-200">Organisasi</p>
                    <p class="text-sm font-normal text-gray-600 dark:text-gray-400 truncate">{{ $permit->booking->organization->name ?? '-' }}</p>
                </div>
            </div>

            <!-- Tanggal -->
            <div class="p-3.5 sm:p-4 rounded-xl bg-gray-50 dark:bg-gray-900/50 border border-gray-100 dark:border-gray-700/50 flex items-center gap-3.5">
                <span class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-brand-50 dark:bg-brand-500/10 text-brand-500 shrink-0">
                    <i class="fa-solid fa-calendar-day text-sm"></i>
                </span>
                <div class="min-w-0">
                    <p class="text-xs font-semibold text-gray-800 dark:text-gray-200">Tanggal</p>
                    <p class="text-sm font-normal text-gray-600 dark:text-gray-400">{{ $permit->booking->booking_date->format('d/m/Y') }}</p>
                </div>
            </div>

            <!-- Waktu -->
            <div class="p-3.5 sm:p-4 rounded-xl bg-gray-50 dark:bg-gray-900/50 border border-gray-100 dark:border-gray-700/50 flex items-center gap-3.5">
                <span class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-brand-50 dark:bg-brand-500/10 text-brand-500 shrink-0">
                    <i class="fa-regular fa-clock text-sm"></i>
                </span>
                <div class="min-w-0">
                    <p class="text-xs font-semibold text-gray-800 dark:text-gray-200">Waktu</p>
                    <p class="text-sm font-normal text-gray-600 dark:text-gray-400">{{ substr($permit->booking->start_time, 0, 5) }} - {{ substr($permit->booking->end_time, 0, 5) }} WIB</p>
                </div>
            </div>

            <!-- Penanggung Jawab -->
            <div class="p-3.5 sm:p-4 rounded-xl bg-gray-50 dark:bg-gray-900/50 border border-gray-100 dark:border-gray-700/50 flex items-center gap-3.5 sm:col-span-2">
                <span class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-brand-50 dark:bg-brand-500/10 text-brand-500 shrink-0">
                    <i class="fa-solid fa-user-tie text-sm"></i>
                </span>
                <div class="min-w-0">
                    <p class="text-xs font-semibold text-gray-800 dark:text-gray-200">Penanggung Jawab</p>
                    <p class="text-sm font-normal text-gray-600 dark:text-gray-400 truncate">{{ $permit->booking->person_in_charge }}</p>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
