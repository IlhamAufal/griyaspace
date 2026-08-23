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
            Informasi validitas surat izin peminjaman ruangan GriyaSpace
        </p>
    </div>

    @php
        $statusConfig = [
            'valid' => [
                'badge_bg' => 'bg-green-50 dark:bg-green-900/20',
                'badge_border' => 'border-green-200 dark:border-green-800/40',
                'badge_text' => 'text-green-700 dark:text-green-400',
                'icon_bg' => 'bg-green-500',
                'icon' => 'fa-solid fa-check',
                'label' => 'Izin Valid',
                'desc' => 'Surat izin peminjaman terdaftar resmi dan sah dalam sistem.'
            ],
            'active' => [
                'badge_bg' => 'bg-blue-50 dark:bg-blue-900/20',
                'badge_border' => 'border-blue-200 dark:border-blue-800/40',
                'badge_text' => 'text-blue-700 dark:text-blue-400',
                'icon_bg' => 'bg-blue-500',
                'icon' => 'fa-solid fa-check-double',
                'label' => 'Sedang Berlangsung',
                'desc' => 'Kegiatan sedang berlangsung hari ini sesuai jadwal izin.'
            ],
            'upcoming' => [
                'badge_bg' => 'bg-amber-50 dark:bg-amber-900/20',
                'badge_border' => 'border-amber-200 dark:border-amber-800/40',
                'badge_text' => 'text-amber-700 dark:text-amber-400',
                'icon_bg' => 'bg-amber-500',
                'icon' => 'fa-solid fa-clock',
                'label' => 'Akan Datang',
                'desc' => 'Izin peminjaman sah untuk kegiatan yang akan datang.'
            ],
            'expired' => [
                'badge_bg' => 'bg-red-50 dark:bg-red-900/20',
                'badge_border' => 'border-red-200 dark:border-red-800/40',
                'badge_text' => 'text-red-700 dark:text-red-400',
                'icon_bg' => 'bg-red-500',
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
                'badge_bg' => 'bg-red-50 dark:bg-red-900/20',
                'badge_border' => 'border-red-200 dark:border-red-800/40',
                'badge_text' => 'text-red-700 dark:text-red-400',
                'icon_bg' => 'bg-red-500',
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
    <!-- Permit Details Card -->
    <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-theme-xs dark:border-gray-800 dark:bg-gray-900/60 mb-6">
        <div class="divide-y divide-gray-100 dark:divide-gray-800">
            @if(!empty($permit->permit_number))
            <div class="pb-3 flex justify-between items-start gap-4">
                <span class="text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Nomor Izin</span>
                <span class="text-sm font-semibold text-gray-900 dark:text-white text-right font-mono">{{ $permit->permit_number }}</span>
            </div>
            @endif
            <div class="{{ !empty($permit->permit_number) ? 'py-3' : 'pb-3' }} flex justify-between items-start gap-4">
                <span class="text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Kegiatan</span>
                <span class="text-sm font-medium text-gray-900 dark:text-white text-right">{{ $permit->booking->activity_name }}</span>
            </div>
            <div class="py-3 flex justify-between items-start gap-4">
                <span class="text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Ruangan</span>
                <span class="text-sm font-medium text-gray-900 dark:text-white text-right">{{ $permit->booking->room->name }}</span>
            </div>
            <div class="py-3 flex justify-between items-start gap-4">
                <span class="text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Tanggal</span>
                <span class="text-sm font-medium text-gray-900 dark:text-white text-right">{{ $permit->booking->booking_date->format('d/m/Y') }}</span>
            </div>
            <div class="py-3 flex justify-between items-start gap-4">
                <span class="text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Waktu</span>
                <span class="text-sm font-medium text-gray-900 dark:text-white text-right">{{ substr($permit->booking->start_time, 0, 5) }} - {{ substr($permit->booking->end_time, 0, 5) }} WIB</span>
            </div>
            <div class="py-3 flex justify-between items-start gap-4">
                <span class="text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Organisasi</span>
                <span class="text-sm font-medium text-gray-900 dark:text-white text-right">{{ $permit->booking->organization->name ?? '-' }}</span>
            </div>
            <div class="pt-3 flex justify-between items-start gap-4">
                <span class="text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Penanggung Jawab</span>
                <span class="text-sm font-medium text-gray-900 dark:text-white text-right">{{ $permit->booking->person_in_charge }}</span>
            </div>
        </div>
    </div>
    @endif

    <div class="text-center">
        <a href="{{ route('login') }}" class="inline-flex items-center gap-2 text-sm font-medium text-brand-500 hover:text-brand-600 dark:text-brand-400 transition-colors">
            <i class="fa-solid fa-arrow-left text-xs"></i>
            Kembali ke Halaman Masuk
        </a>
    </div>
</div>
@endsection
