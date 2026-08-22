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
                    'valid' => ['color' => 'bg-green-500', 'icon' => 'fa-solid fa-check', 'text' => 'text-green-700', 'bg' => 'bg-green-50', 'label' => 'Valid'],
                    'active' => ['color' => 'bg-blue-500', 'icon' => 'fa-solid fa-check', 'text' => 'text-blue-700', 'bg' => 'bg-blue-50', 'label' => 'Aktif'],
                    'upcoming' => ['color' => 'bg-yellow-500', 'icon' => 'fa-solid fa-clock', 'text' => 'text-yellow-700', 'bg' => 'bg-yellow-50', 'label' => 'Akan Datang'],
                    'expired' => ['color' => 'bg-red-500', 'icon' => 'fa-solid fa-xmark', 'text' => 'text-red-700', 'bg' => 'bg-red-50', 'label' => 'Kadaluarsa'],
                    'revoked' => ['color' => 'bg-gray-500', 'icon' => 'fa-solid fa-ban', 'text' => 'text-gray-700', 'bg' => 'bg-gray-50', 'label' => 'Dicabut'],
                    'not_found' => ['color' => 'bg-gray-400', 'icon' => 'fa-solid fa-question', 'text' => 'text-gray-700', 'bg' => 'bg-gray-50', 'label' => 'Tidak Ditemukan'],
                ];
                $config = $statusConfig[$status] ?? $statusConfig['not_found'];
            @endphp

            <div class="rounded-lg {{ $config['bg'] }} p-4 mb-6">
                <div class="flex items-center">
                    <span class="flex-shrink-0 w-10 h-10 {{ $config['color'] }} rounded-full flex items-center justify-center text-white">
                        <i class="{{ $config['icon'] }} text-lg"></i>
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
                    <p class="text-sm font-medium text-gray-900">{{ substr($permit->booking->start_time, 0, 5) }} - {{ substr($permit->booking->end_time, 0, 5) }} WIB</p>
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
