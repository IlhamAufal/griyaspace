@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Daftar Ruangan</h1>
            <a href="{{ route('rooms.create') }}" class="bg-brand-500 hover:bg-brand-600 text-white px-4 py-2 rounded-lg inline-flex items-center gap-2">
                <i class="fa-solid fa-plus text-sm"></i>
                <span>Ruangan Baru</span>
            </a>
        </div>

        <div class="bg-white shadow rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-brand-500 text-white">
                    <tr>
                        <th class="px-6 py-3.5 text-left text-xs font-bold text-white uppercase tracking-wider">Kode</th>
                        <th class="px-6 py-3.5 text-left text-xs font-bold text-white uppercase tracking-wider">Nama</th>
                        <th class="px-6 py-3.5 text-left text-xs font-bold text-white uppercase tracking-wider">Kapasitas</th>
                        <th class="px-6 py-3.5 text-left text-xs font-bold text-white uppercase tracking-wider">Lokasi</th>
                        <th class="px-6 py-3.5 text-left text-xs font-bold text-white uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3.5 text-left text-xs font-bold text-white uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($rooms as $room)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $room->code }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $room->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $room->capacity }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $room->location }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $statusColor = $room->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
                                $statusLabel = $room->status === 'active' ? 'Aktif' : 'Nonaktif';
                            @endphp
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColor }}">
                                {{ $statusLabel }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('rooms.edit', $room) }}" class="inline-flex items-center justify-center w-8 h-8 text-blue-600 hover:text-blue-900 hover:bg-blue-50 rounded-lg transition-colors dark:text-blue-400 dark:hover:bg-blue-900/20" title="Edit Ruangan">
                                    <i class="fa-solid fa-pen-to-square text-sm"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">Tidak ada data ruangan</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $rooms->links() }}
        </div>
    </div>
</div>
@endsection
