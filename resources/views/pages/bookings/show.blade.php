@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">Detail Pengajuan</h1>

        <div class="bg-white shadow rounded-lg p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-500">No. Booking</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $booking->booking_number }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-500">Status</label>
                    @php
                        $statusColors = [
                            'submitted' => 'bg-yellow-100 text-yellow-800',
                            'approved' => 'bg-green-100 text-green-800',
                            'rejected' => 'bg-red-100 text-red-800',
                            'revision' => 'bg-orange-100 text-orange-800',
                            'cancelled' => 'bg-gray-100 text-gray-800',
                        ];
                        $statusLabels = [
                            'submitted' => 'Diajukan',
                            'approved' => 'Disetujui',
                            'rejected' => 'Ditolak',
                            'revision' => 'Revisi',
                            'cancelled' => 'Dibatalkan',
                        ];
                    @endphp
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColors[$booking->status] ?? 'bg-gray-100 text-gray-800' }}">
                        {{ $statusLabels[$booking->status] ?? $booking->status }}
                    </span>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-500">Ruangan</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $booking->room->name }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-500">Tanggal</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $booking->booking_date->format('d/m/Y') }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-500">Waktu</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $booking->start_time }} - {{ $booking->end_time }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-500">Kegiatan</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $booking->activity_name }}</p>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-500">Tujuan</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $booking->purpose }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-500">Jumlah Peserta</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $booking->participant_count }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-500">Penanggung Jawab</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $booking->person_in_charge }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-500">No. Telepon</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $booking->contact_phone }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-500">Organisasi</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $booking->organization->name ?? '-' }}</p>
                </div>

                @if($booking->document_path)
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-500">Dokumen</label>
                    <a href="{{ Storage::url($booking->document_path) }}" target="_blank" class="mt-1 inline-flex items-center text-blue-600 hover:text-blue-900">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                        Lihat Dokumen
                    </a>
                </div>
                @endif

                @if($booking->permit)
                <div class="md:col-span-2 border-t pt-4">
                    <h3 class="text-lg font-medium text-gray-900 mb-3">Izin</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Status Izin</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $booking->permit->status }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">QR Code</label>
                            @if($booking->permit->qr_code)
                            <img src="{{ Storage::url($booking->permit->qr_code) }}" alt="QR Code" class="mt-1 h-24">
                            @endif
                        </div>
                    </div>
                </div>
                @endif
            </div>

            @if($booking->history->count())
            <div class="mt-6 border-t pt-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Riwayat</h3>
                <div class="flow-root">
                    <ul class="-mb-8">
                        @foreach($booking->history as $index => $history)
                        <li>
                            <div class="relative pb-8">
                                @if(!$loop->last)
                                <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200"></span>
                                @endif
                                <div class="relative flex space-x-3">
                                    <div>
                                        <span class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center ring-8 ring-white">
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        </span>
                                    </div>
                                    <div class="flex min-w-0 flex-1 justify-between space-x-4 pt-1.5">
                                        <div>
                                            <p class="text-sm text-gray-500">{{ $history->description }}</p>
                                        </div>
                                        <div class="whitespace-nowrap text-right text-sm text-gray-500">
                                            {{ $history->created_at->format('d/m/Y H:i') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif

            @if($booking->status === 'submitted')
            <div class="mt-6 border-t pt-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Aksi Admin</h3>
                <form action="{{ route('bookings.approve', $booking) }}" method="POST" class="inline-block">
                    @csrf
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">Setujui</button>
                </form>
                <form action="{{ route('bookings.reject', $booking) }}" method="POST" class="inline-block ml-2">
                    @csrf
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg">Tolak</button>
                </form>
                <form action="{{ route('bookings.revision', $booking) }}" method="POST" class="inline-block ml-2">
                    @csrf
                    <button type="submit" class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg">Minta Revisi</button>
                </form>
            </div>
            @endif

            @if(in_array($booking->status, ['submitted', 'revision']))
            <div class="mt-6 border-t pt-6">
                <form action="{{ route('bookings.cancel', $booking) }}" method="POST" onsubmit="return confirm('Yakin ingin membatalkan pengajuan ini?')">
                    @csrf
                    <button type="submit" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">Batalkan Pengajuan</button>
                </form>
            </div>
            @endif

            <div class="mt-6 flex justify-end">
                <a href="{{ route('bookings.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-lg">Kembali</a>
            </div>
        </div>
    </div>
</div>
@endsection
