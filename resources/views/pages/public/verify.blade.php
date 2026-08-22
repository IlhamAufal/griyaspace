@extends('layouts.fullscreen-layout')

@section('content')
<div class="min-h-screen bg-gray-100 flex items-center justify-center p-4">
    <div class="max-w-md w-full bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="p-6">
            <div class="text-center mb-6">
                <h1 class="text-2xl font-bold text-gray-900">Verifikasi Izin</h1>
                <p class="text-sm text-gray-500 mt-1">GriyaSpace</p>
            </div>

            @php
                $statusConfig = [
                    'valid' => ['color' => 'bg-green-500', 'icon' => 'M5 13l4 4L19 7', 'text' => 'text-green-700', 'bg' => 'bg-green-50', 'label' => 'Valid'],
                    'active' => ['color' => 'bg-blue-500', 'icon' => 'M5 13l4 4L19 7', 'text' => 'text-blue-700', 'bg' => 'bg-blue-50', 'label' => 'Aktif'],
                    'upcoming' => ['color' => 'bg-yellow-500', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', 'text' => 'text-yellow-700', 'bg' => 'bg-yellow-50', 'label' => 'Akan Datang'],
                    'expired' => ['color' => 'bg-red-500', 'icon' => 'M6 18L18 6M6 6l12 12', 'text' => 'text-red-700', 'bg' => 'bg-red-50', 'label' => 'Kadaluarsa'],
                    'revoked' => ['color' => 'bg-gray-500', 'icon' => 'M6 18L18 6M6 6l12 12', 'text' => 'text-gray-700', 'bg' => 'bg-gray-50', 'label' => 'Dicabut'],
                    'not_found' => ['color' => 'bg-gray-400', 'icon' => 'M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'text' => 'text-gray-700', 'bg' => 'bg-gray-50', 'label' => 'Tidak Ditemukan'],
                ];
                $config = $statusConfig[$status] ?? $statusConfig['not_found'];
            @endphp

            <div class="rounded-lg {{ $config['bg'] }} p-4 mb-6">
                <div class="flex items-center">
                    <span class="flex-shrink-0 w-10 h-10 {{ $config['color'] }} rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $config['icon'] }}"></path>
                        </svg>
                    </span>
                    <div class="ml-3">
                        <h3 class="text-lg font-medium {{ $config['text'] }}">{{ $config['label'] }}</h3>
                    </div>
                </div>
            </div>

            @if(in_array($status, ['valid', 'active']) && $permit)
            <div class="space-y-3">
                <div class="border-b pb-3">
                    <p class="text-sm text-gray-500">Kegiatan</p>
                    <p class="text-sm font-medium text-gray-900">{{ $permit->booking->activity_name }}</p>
                </div>
                <div class="border-b pb-3">
                    <p class="text-sm text-gray-500">Ruangan</p>
                    <p class="text-sm font-medium text-gray-900">{{ $permit->booking->room->name }}</p>
                </div>
                <div class="border-b pb-3">
                    <p class="text-sm text-gray-500">Tanggal</p>
                    <p class="text-sm font-medium text-gray-900">{{ $permit->booking->booking_date->format('d/m/Y') }}</p>
                </div>
                <div class="border-b pb-3">
                    <p class="text-sm text-gray-500">Waktu</p>
                    <p class="text-sm font-medium text-gray-900">{{ $permit->booking->start_time }} - {{ $permit->booking->end_time }}</p>
                </div>
                <div class="border-b pb-3">
                    <p class="text-sm text-gray-500">Organisasi</p>
                    <p class="text-sm font-medium text-gray-900">{{ $permit->booking->organization->name ?? '-' }}</p>
                </div>
                <div class="border-b pb-3">
                    <p class="text-sm text-gray-500">Penanggung Jawab</p>
                    <p class="text-sm font-medium text-gray-900">{{ $permit->booking->person_in_charge }}</p>
                </div>
            </div>
            @endif

            @if($status === 'not_found')
            <div class="text-center text-gray-500">
                <p class="text-sm">Izin tidak ditemukan atau tidak valid.</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
