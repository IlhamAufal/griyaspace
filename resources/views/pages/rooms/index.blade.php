@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="flex mb-4" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3 text-sm text-gray-500 dark:text-gray-400">
                <li class="inline-flex items-center">
                    <a href="{{ route('dashboard') }}" class="hover:text-brand-500 dark:hover:text-brand-400 inline-flex items-center gap-1.5">
                        <i class="fa-solid fa-house text-xs"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <i class="fa-solid fa-chevron-right text-gray-400 text-xs mx-1.5"></i>
                        <span class="text-gray-700 dark:text-gray-200 font-medium">Ruangan</span>
                    </div>
                </li>
            </ol>
        </nav>

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Daftar Ruangan</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Kelola data ruangan, kapasitas, jam operasional, dan fasilitas</p>
            </div>
            {{-- <a href="{{ route('rooms.create') }}" class="bg-accent-500 hover:bg-accent-600 text-white px-4 py-2.5 rounded-lg inline-flex items-center text-sm font-medium shadow-theme-xs transition-colors self-start sm:self-auto">
                <span>Ruangan Baru</span>
            </a> --}}
        </div>

        <!-- Room Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($rooms as $room)
                @php
                    $coverPhoto = $room->photos->first();
                    $imageSrc = $coverPhoto
                        ? asset('storage/' . $coverPhoto->photo_path)
                        : asset('images/cards/card-01.jpg');
                    $isInactive = $room->status !== 'active';
                @endphp
                <div class="relative group flex flex-col bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700/60 shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden">
                    <!-- Inactive 60% Black Masking Overlay -->
                    @if($isInactive)
                        <div class="absolute inset-0 bg-black/90 backdrop-blur-[2px] z-20 flex flex-col items-center justify-center p-6 text-center">
                            @if(auth()->user()?->isAdmin())
                                <div class="w-12 h-12 rounded-full bg-white/15 flex items-center justify-center mb-3 text-red-400 border border-white/10 shadow-sm">
                                    <i class="fa-solid fa-ban text-xl"></i>
                                </div>
                                <h4 class="text-base font-bold text-white mb-1.5">Ruangan sedang di Non-Aktifkan</h4>
                                <p class="text-xs text-gray-200 max-w-[230px] mb-4">Ruangan ini tidak aktif dan tidak dapat diajukan untuk peminjaman.</p>
                                <a href="{{ route('rooms.edit', $room) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-xs font-semibold bg-white text-gray-900 hover:bg-gray-100 shadow-md transition-colors">
                                    <i class="fa-solid fa-pen-to-square text-xs text-blue-600"></i>
                                    <span>Edit & Aktifkan</span>
                                </a>
                            @else
                                <div class="w-12 h-12 rounded-full bg-white/15 flex items-center justify-center mb-3 text-amber-300 border border-white/10 shadow-sm">
                                    <i class="fa-solid fa-circle-exclamation text-xl"></i>
                                </div>
                                <h4 class="text-sm font-semibold text-white leading-relaxed max-w-[240px]">
                                    Ruangan sedang di nonaktifkan, hubungi admin untuk selengkapnya
                                </h4>
                            @endif
                        </div>
                    @endif

                    <!-- Photo Header with Image Skeleton Loading -->
                    <div class="relative h-48 w-full overflow-hidden bg-gray-100 dark:bg-gray-700"
                         x-data="{ imgLoaded: false }"
                         x-init="if ($refs.roomImg && $refs.roomImg.complete) imgLoaded = true">

                        <!-- Skeleton Shimmer Placeholder -->
                        <div x-show="!imgLoaded"
                             class="absolute inset-0 bg-gray-200 dark:bg-gray-700 animate-pulse flex flex-col items-center justify-center text-gray-400 dark:text-gray-500 z-0">
                            <i class="fa-regular fa-image text-3xl mb-1 opacity-50"></i>
                            <span class="text-[11px] font-medium opacity-60">Memuat visual...</span>
                        </div>

                        <!-- Room Image with Smooth Transition -->
                        <img x-ref="roomImg"
                             src="{{ $imageSrc }}"
                             alt="{{ $room->name }}"
                             loading="lazy"
                             @load="imgLoaded = true"
                             class="h-full w-full object-cover group-hover:scale-105 transition-all duration-500"
                             :class="imgLoaded ? 'opacity-100' : 'opacity-0'">

                        <div class="absolute inset-0 bg-gradient-to-t from-gray-900/80 via-gray-900/20 to-black/30 pointer-events-none"></div>

                        <!-- Room Name Overlay (Bottom) -->
                        <div class="absolute bottom-3 left-3 right-3 z-10">
                            <h3 class="text-lg font-bold text-white drop-shadow-sm truncate" title="{{ $room->name }}">
                                {{ $room->name }}
                            </h3>
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="p-5 flex-1 flex flex-col justify-between space-y-4">
                        <div class="space-y-3">
                            <!-- Lokasi -->
                            <div class="flex items-start gap-2.5 text-sm text-gray-600 dark:text-gray-300">
                                <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-brand-50 dark:bg-brand-500/10 text-brand-500 dark:text-brand-400 shrink-0 mt-0.5">
                                    <i class="fa-solid fa-location-dot text-xs"></i>
                                </span>
                                <div class="min-w-0 flex-1">
                                    <p class="text-xs text-gray-400 dark:text-gray-500">Lokasi / Gedung</p>
                                    <p class="font-medium text-gray-800 dark:text-gray-200 truncate">{{ $room->location }}</p>
                                </div>
                            </div>

                            <!-- Kapasitas & Jam Operasional -->
                            <div class="grid grid-cols-2 gap-2 pt-1">
                                <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-300">
                                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-brand-50 dark:bg-brand-500/10 text-brand-500 dark:text-brand-400 shrink-0">
                                        <i class="fa-solid fa-users text-xs"></i>
                                    </span>
                                    <div class="min-w-0">
                                        <p class="text-xs text-gray-400 dark:text-gray-500">Kapasitas</p>
                                        <p class="font-semibold text-gray-800 dark:text-gray-200">{{ $room->capacity }} Org</p>
                                    </div>
                                </div>

                                <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-300">
                                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-brand-50 dark:bg-brand-500/10 text-brand-500 dark:text-brand-400 shrink-0">
                                        <i class="fa-regular fa-clock text-xs"></i>
                                    </span>
                                    <div class="min-w-0">
                                        <p class="text-xs text-gray-400 dark:text-gray-500">Jam Buka</p>
                                        <p class="font-semibold text-gray-800 dark:text-gray-200 truncate">{{ substr($room->open_time, 0, 5) }} - {{ substr($room->close_time, 0, 5) }}</p>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <!-- Card Footer Actions -->
                        <div class="pt-4 border-t border-gray-100 dark:border-gray-700/60 flex items-center justify-end gap-2">
                            <a href="{{ route('rooms.show', $room) }}" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-lg text-xs font-semibold text-green-600 hover:text-green-800 bg-green-50 hover:bg-green-100 dark:text-green-400 dark:bg-green-900/20 dark:hover:bg-green-900/40 transition-colors" title="Lihat Detail">
                                <i class="fa-solid fa-eye text-xs"></i>
                                <span>Detail</span>
                            </a>
                            @if(auth()->user()?->isAdmin())
                            <a href="{{ route('rooms.edit', $room) }}" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-lg text-xs font-semibold text-blue-600 hover:text-blue-800 bg-blue-50 hover:bg-blue-100 dark:text-blue-400 dark:bg-blue-900/20 dark:hover:bg-blue-900/40 transition-colors" title="Edit Ruangan">
                                <i class="fa-solid fa-pen-to-square text-xs"></i>
                                <span>Edit</span>
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700/60 p-12 text-center shadow-sm">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-brand-50 dark:bg-brand-500/10 text-brand-500 mb-4">
                        <i class="fa-solid fa-door-open text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">Belum Ada Ruangan</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Mulai tambahkan ruangan baru ke sistem GriyaSpace</p>
                    <a href="{{ route('rooms.create') }}" class="bg-accent-500 hover:bg-accent-600 text-white px-5 py-2.5 rounded-lg inline-flex items-center gap-2 text-sm font-medium shadow-theme-xs transition-colors">
                        <span>Ruangan Baru</span>
                    </a>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($rooms->hasPages())
        <div class="mt-8">
            {{ $rooms->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
