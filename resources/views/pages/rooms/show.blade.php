@extends('layouts.app')

@section('content')
<div class="py-6" x-data="{
    activeIndex: 0,
    lightbox: false,
    photos: [
        { url: '{{ asset('images/cards/card-01.jpg') }}', caption: 'Tampak Depan & Panggung Utama', tag: 'Panggung & Podium' },
        { url: '{{ asset('images/cards/card-02.jpg') }}', caption: 'Area Kursi & Layout Peserta', tag: 'Interior Ruangan' },
        { url: '{{ asset('images/cards/card-03.jpg') }}', caption: 'Meja Pembicara & Area Presentasi', tag: 'Meja Narasumber' },
        { url: '{{ asset('images/carousel/carousel-01.png') }}', caption: 'Fasilitas Proyektor & Layar Lebar', tag: 'Multimedia & Layar' },
        { url: '{{ asset('images/carousel/carousel-02.png') }}', caption: 'Pintu Masuk & Foyer Ruangan', tag: 'Akses & Foyer' },
        { url: '{{ asset('images/carousel/carousel-03.png') }}', caption: 'Tata Udara & Pencahayaan Ruangan', tag: 'Fasilitas AC' }
    ],
    next() {
        this.activeIndex = (this.activeIndex + 1) % this.photos.length;
    },
    prev() {
        this.activeIndex = (this.activeIndex - 1 + this.photos.length) % this.photos.length;
    },
    openLightbox(index) {
        this.activeIndex = index;
        this.lightbox = true;
    }
}" @keydown.escape.window="lightbox = false" @keydown.arrow-left.window="if (lightbox) prev()" @keydown.arrow-right.window="if (lightbox) next()">

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
                <li>
                    <div class="flex items-center">
                        <i class="fa-solid fa-chevron-right text-gray-400 text-xs mx-1.5"></i>
                        <a href="{{ route('rooms.index') }}" class="hover:text-brand-500 dark:hover:text-brand-400">Ruangan</a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <i class="fa-solid fa-chevron-right text-gray-400 text-xs mx-1.5"></i>
                        <span class="text-gray-700 dark:text-gray-200 font-medium truncate max-w-[200px] sm:max-w-xs">{{ $room->name }}</span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
            <div class="space-y-1">
                <div class="flex flex-wrap items-center gap-3">
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">{{ $room->name }}</h1>                    
                </div>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('rooms.index') }}" class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600 px-4 py-2.5 rounded-lg text-sm font-medium transition-colors">
                    <i class="fa-solid fa-arrow-left text-sm"></i>
                    <span>Daftar Ruangan</span>
                </a>
                @if(auth()->user()?->isAdmin())
                <a href="{{ route('rooms.edit', $room) }}" class="inline-flex items-center gap-2 bg-brand-500 hover:bg-brand-600 text-white px-4 py-2.5 rounded-lg text-sm font-medium shadow-theme-xs transition-colors">
                    <i class="fa-solid fa-pen-to-square text-sm"></i>
                    <span>Edit Ruangan</span>
                </a>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left 2 Cols: Multi-Image Carousel & Main Info -->
            <div class="lg:col-span-2 space-y-8">

                <!-- SECTION 1: Interactive Multi-Image Carousel -->
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-2xl border border-gray-200 dark:border-gray-700/60 p-5 sm:p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-2.5">
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-brand-50 dark:bg-brand-500/10 text-brand-500">
                                <i class="fa-solid fa-images text-sm"></i>
                            </span>
                            <div>
                                <h2 class="text-base font-bold text-gray-900 dark:text-white">Galeri Foto Ruangan</h2>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Klik atau geser untuk melihat visual ruangan</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-semibold px-2.5 py-1 rounded-md bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                                <span x-text="activeIndex + 1"></span> / <span x-text="photos.length"></span> Foto
                            </span>
                        </div>
                    </div>

                    <!-- Main Stage Image with Navigation Overlay -->
                    <div class="relative h-72 sm:h-96 md:h-[420px] w-full rounded-2xl overflow-hidden bg-gray-900 shadow-inner group">
                        <!-- Active Image -->
                        <img :src="photos[activeIndex].url" :alt="photos[activeIndex].caption" class="w-full h-full object-cover transition-all duration-500 cursor-pointer" @click="lightbox = true">

                        <!-- Ambient Gradient Overlay -->
                        <div class="absolute inset-0 bg-gradient-to-t from-gray-950/80 via-transparent to-black/20 pointer-events-none"></div>

                        <!-- Prev Button -->
                        <button type="button" @click="prev()" class="absolute left-3 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-black/40 hover:bg-black/70 text-white backdrop-blur-xs flex items-center justify-center transition-all opacity-80 hover:opacity-100 hover:scale-110 shadow-md" aria-label="Foto Sebelumnya">
                            <i class="fa-solid fa-chevron-left text-sm"></i>
                        </button>

                        <!-- Next Button -->
                        <button type="button" @click="next()" class="absolute right-3 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-black/40 hover:bg-black/70 text-white backdrop-blur-xs flex items-center justify-center transition-all opacity-80 hover:opacity-100 hover:scale-110 shadow-md" aria-label="Foto Selanjutnya">
                            <i class="fa-solid fa-chevron-right text-sm"></i>
                        </button>

                        <!-- Bottom Info Overlay -->
                        <div class="absolute bottom-4 left-4 right-4 flex items-end justify-between gap-4">
                            <div class="space-y-1">
                                <span class="inline-block px-2.5 py-0.5 rounded-md bg-brand-500/90 text-white text-xs font-semibold backdrop-blur-xs shadow-xs" x-text="photos[activeIndex].tag"></span>
                                <h3 class="text-white font-bold text-base sm:text-lg drop-shadow-md" x-text="photos[activeIndex].caption"></h3>
                            </div>
                            <button type="button" @click="lightbox = true" class="inline-flex items-center gap-1.5 bg-white/20 hover:bg-white/30 text-white backdrop-blur-xs px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors shrink-0 shadow-sm" title="Lihat Layar Penuh">
                                <i class="fa-solid fa-expand"></i>
                                <span class="hidden sm:inline">Perbesar</span>
                            </button>
                        </div>
                    </div>

                    <!-- Multi-Image Thumbnails Strip -->
                    <div class="grid grid-cols-6 gap-2 sm:gap-3 mt-3.5">
                        <template x-for="(photo, index) in photos" :key="index">
                            <button type="button" @click="activeIndex = index" class="relative rounded-xl overflow-hidden aspect-video transition-all duration-200 group focus:outline-hidden" :class="activeIndex === index ? 'ring-3 ring-brand-500 shadow-md scale-102 opacity-100' : 'opacity-60 hover:opacity-100 hover:scale-101'">
                                <img :src="photo.url" :alt="photo.caption" class="w-full h-full object-cover">
                                <div class="absolute inset-0 bg-black/20 group-hover:bg-transparent transition-colors"></div>
                                <div x-show="activeIndex === index" class="absolute bottom-0 inset-x-0 h-1 bg-brand-500"></div>
                            </button>
                        </template>
                    </div>
                </div>

                <!-- SECTION 2: Spesifikasi & Informasi Lengkap Ruangan -->
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-2xl border border-gray-200 dark:border-gray-700/60 p-6 sm:p-8">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-5 flex items-center gap-2.5">
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-brand-50 dark:bg-brand-500/10 text-brand-500">
                            <i class="fa-solid fa-sliders text-sm"></i>
                        </span>
                        <span>Spesifikasi & Informasi Ruangan</span>
                    </h2>

                    <!-- 6 Grid Quick Stats -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                        <!-- Kode -->
                        <div class="p-4 rounded-xl bg-gray-50 dark:bg-gray-900/50 border border-gray-100 dark:border-gray-700/50 flex items-center gap-3.5">
                            <span class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-brand-50 dark:bg-brand-500/10 text-brand-500 shrink-0">
                                <i class="fa-solid fa-barcode text-sm"></i>
                            </span>
                            <div class="min-w-0">
                                <p class="text-xs font-semibold text-gray-800 dark:text-gray-200">Kode Ruangan</p>
                                <p class="text-sm font-normal text-gray-600 dark:text-gray-400 truncate font-mono">{{ $room->code }}</p>
                            </div>
                        </div>

                        <!-- Kapasitas -->
                        <div class="p-4 rounded-xl bg-gray-50 dark:bg-gray-900/50 border border-gray-100 dark:border-gray-700/50 flex items-center gap-3.5">
                            <span class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-brand-50 dark:bg-brand-500/10 text-brand-500 shrink-0">
                                <i class="fa-solid fa-users text-sm"></i>
                            </span>
                            <div class="min-w-0">
                                <p class="text-xs font-semibold text-gray-800 dark:text-gray-200">Kapasitas Maksimal</p>
                                <p class="text-sm font-normal text-gray-600 dark:text-gray-400 truncate">{{ $room->capacity }} Orang</p>
                            </div>
                        </div>

                        <!-- Lokasi -->
                        <div class="p-4 rounded-xl bg-gray-50 dark:bg-gray-900/50 border border-gray-100 dark:border-gray-700/50 flex items-center gap-3.5">
                            <span class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-brand-50 dark:bg-brand-500/10 text-brand-500 shrink-0">
                                <i class="fa-solid fa-location-dot text-sm"></i>
                            </span>
                            <div class="min-w-0">
                                <p class="text-xs font-semibold text-gray-800 dark:text-gray-200">Lokasi / Gedung</p>
                                <p class="text-sm font-normal text-gray-600 dark:text-gray-400 truncate" title="{{ $room->location }}">{{ $room->location }}</p>
                            </div>
                        </div>

                        <!-- Jam Buka -->
                        <div class="p-4 rounded-xl bg-gray-50 dark:bg-gray-900/50 border border-gray-100 dark:border-gray-700/50 flex items-center gap-3.5">
                            <span class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-brand-50 dark:bg-brand-500/10 text-brand-500 shrink-0">
                                <i class="fa-regular fa-clock text-sm"></i>
                            </span>
                            <div class="min-w-0">
                                <p class="text-xs font-semibold text-gray-800 dark:text-gray-200">Jam Buka</p>
                                <p class="text-sm font-normal text-gray-600 dark:text-gray-400">{{ substr($room->open_time, 0, 5) }} WIB</p>
                            </div>
                        </div>

                        <!-- Jam Tutup -->
                        <div class="p-4 rounded-xl bg-gray-50 dark:bg-gray-900/50 border border-gray-100 dark:border-gray-700/50 flex items-center gap-3.5">
                            <span class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-brand-50 dark:bg-brand-500/10 text-brand-500 shrink-0">
                                <i class="fa-solid fa-clock-rotate-left text-sm"></i>
                            </span>
                            <div class="min-w-0">
                                <p class="text-xs font-semibold text-gray-800 dark:text-gray-200">Jam Tutup</p>
                                <p class="text-sm font-normal text-gray-600 dark:text-gray-400">{{ substr($room->close_time, 0, 5) }} WIB</p>
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="p-4 rounded-xl bg-gray-50 dark:bg-gray-900/50 border border-gray-100 dark:border-gray-700/50 flex items-center gap-3.5">
                            <span class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-brand-50 dark:bg-brand-500/10 text-brand-500 shrink-0">
                                <i class="fa-solid fa-circle-check text-sm"></i>
                            </span>
                            <div class="min-w-0">
                                <p class="text-xs font-semibold text-gray-800 dark:text-gray-200">Status Ruangan</p>
                                <p class="text-sm font-normal {{ $room->isActive() ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                    {{ $room->isActive() ? 'Tersedia (Aktif)' : 'Tidak Aktif' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SECTION 3: Jadwal Pengajuan / Pemakaian Terkini -->
                @if(isset($recentBookings) && $recentBookings->count() > 0)
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-2xl border border-gray-200 dark:border-gray-700/60 p-6 sm:p-8">
                    <h2 class="text-base font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2.5">
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-brand-50 dark:bg-brand-500/10 text-brand-500">
                            <i class="fa-solid fa-calendar-days text-sm"></i>
                        </span>
                        <span>Jadwal Pemakaian Ruangan Terakhir</span>
                    </h2>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-100 dark:divide-gray-700 text-sm">
                            <thead>
                                <tr class="text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    <th class="py-3 px-3">Organisasi</th>
                                    <th class="py-3 px-3">Tanggal</th>
                                    <th class="py-3 px-3">Jam</th>
                                    <th class="py-3 px-3">Kegiatan</th>
                                    <th class="py-3 px-3">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700 text-gray-700 dark:text-gray-300">
                                @foreach($recentBookings as $b)
                                <tr>
                                    <td class="py-3 px-3 font-medium text-gray-900 dark:text-white">{{ $b->organization->name ?? ($b->guest_name ?? '-') }}</td>
                                    <td class="py-3 px-3">{{ \Carbon\Carbon::parse($b->start_time)->format('d M Y') }}</td>
                                    <td class="py-3 px-3">{{ \Carbon\Carbon::parse($b->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($b->end_time)->format('H:i') }}</td>
                                    <td class="py-3 px-3 truncate max-w-[180px]">{{ $b->event_name ?? $b->purpose ?? '-' }}</td>
                                    <td class="py-3 px-3">
                                        @if($b->status === 'approved')
                                            <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">Disetujui</span>
                                        @elseif($b->status === 'submitted' || $b->status === 'pending')
                                            <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400">Diajukan</span>
                                        @else
                                            <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">{{ ucfirst($b->status) }}</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif

            </div>

            <!-- Right Sidebar: Quick Information & Support -->
            <div class="space-y-6">

                <!-- Ringkasan Ruangan -->
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-2xl border border-gray-200 dark:border-gray-700/60 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700/60">
                        <h2 class="text-base font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                            <i class="fa-solid fa-chart-simple text-brand-500 text-sm"></i>
                            Ringkasan
                        </h2>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Kode Ruangan</span>
                            <span class="text-sm font-semibold text-gray-800 dark:text-gray-200 bg-gray-100 dark:bg-gray-700 px-2.5 py-0.5 rounded-md">{{ $room->code }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Kapasitas</span>
                            <span class="text-sm font-semibold text-gray-800 dark:text-gray-200">{{ $room->capacity }} Orang</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Jam Operasional</span>
                            <span class="text-sm font-semibold text-gray-800 dark:text-gray-200">{{ substr($room->open_time, 0, 5) }} - {{ substr($room->close_time, 0, 5) }}</span>
                        </div>
                        <hr class="border-gray-100 dark:border-gray-700/60">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Total Pemakaian</span>
                            <span class="text-sm font-bold text-brand-500">{{ $room->bookings()->count() }} Kali Booking</span>
                        </div>
                    </div>
                </div>

                <!-- Hubungi Pengelola -->
                <div class="bg-gradient-to-br from-brand-500 to-brand-700 text-white rounded-2xl p-6 shadow-sm">
                    <h3 class="text-base font-bold mb-1 flex items-center gap-2">
                        <i class="fa-solid fa-headset"></i>
                        <span>Pusat Bantuan & Pengelola</span>
                    </h3>
                    <p class="text-xs text-white/80 mb-4 leading-relaxed">
                        Butuh konfirmasi ketersediaan khusus atau informasi sarana prasarana untuk ruangan ini?
                    </p>
                    <div class="space-y-2 text-xs">
                        <div class="flex items-center gap-2 text-white/90">
                            <i class="fa-solid fa-building text-accent-300 w-4 text-center"></i>
                            <span>Biro Administrasi & Sarana UMS</span>
                        </div>
                        <div class="flex items-center gap-2 text-white/90">
                            <i class="fa-solid fa-envelope text-accent-300 w-4 text-center"></i>
                            <span>sarpras@ums.ac.id</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- FULLSCREEN LIGHTBOX MODAL -->
    <div x-show="lightbox" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 z-[99999] bg-black/95 backdrop-blur-md flex flex-col justify-between p-4 sm:p-6" style="display: none;">
        <!-- Top Bar -->
        <div class="flex items-center justify-between text-white z-10">
            <div class="flex items-center gap-3">
                <span class="text-sm font-bold bg-white/20 px-3 py-1 rounded-lg" x-text="photos[activeIndex].tag"></span>
                <span class="text-sm font-medium text-gray-300" x-text="photos[activeIndex].caption"></span>
            </div>
            <button type="button" @click="lightbox = false" class="w-10 h-10 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition-colors">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <!-- Center Image Stage -->
        <div class="relative flex-1 flex items-center justify-center my-4 overflow-hidden">
            <img :src="photos[activeIndex].url" :alt="photos[activeIndex].caption" class="max-h-[75vh] max-w-full object-contain rounded-xl shadow-2xl transition-all duration-300">

            <button type="button" @click="prev()" class="absolute left-2 sm:left-4 top-1/2 -translate-y-1/2 w-12 h-12 rounded-full bg-black/50 hover:bg-black/80 text-white flex items-center justify-center transition-colors">
                <i class="fa-solid fa-chevron-left text-lg"></i>
            </button>

            <button type="button" @click="next()" class="absolute right-2 sm:right-4 top-1/2 -translate-y-1/2 w-12 h-12 rounded-full bg-black/50 hover:bg-black/80 text-white flex items-center justify-center transition-colors">
                <i class="fa-solid fa-chevron-right text-lg"></i>
            </button>
        </div>

        <!-- Bottom Thumbnails Bar -->
        <div class="flex items-center justify-center gap-2 overflow-x-auto py-2 z-10">
            <template x-for="(photo, index) in photos" :key="index">
                <button type="button" @click="activeIndex = index" class="w-16 h-12 rounded-lg overflow-hidden shrink-0 transition-all" :class="activeIndex === index ? 'ring-2 ring-accent-400 scale-105 opacity-100' : 'opacity-50 hover:opacity-100'">
                    <img :src="photo.url" :alt="photo.caption" class="w-full h-full object-cover">
                </button>
            </template>
        </div>
    </div>

</div>
@endsection
