@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400 mb-6">
            <a href="{{ route('dashboard') }}" class="hover:text-brand-500 transition-colors"><i class="fa-solid fa-house"></i></a>
            <i class="fa-solid fa-chevron-right text-xs"></i>
            <a href="{{ route('rooms.index') }}" class="hover:text-brand-500 transition-colors">Ruangan</a>
            <i class="fa-solid fa-chevron-right text-xs"></i>
            <span class="text-gray-900 dark:text-white font-medium">Edit Ruangan</span>
        </nav>

        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Edit Ruangan: {{ $room->name }}</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Perbarui data spesifikasi ruangan dan kelola galeri foto visual ruangan.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('rooms.show', $room) }}" class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600 px-4 py-2.5 rounded-lg text-sm font-medium transition-colors">
                    <i class="fa-solid fa-eye text-sm"></i>
                    <span>Lihat Detail Ruangan</span>
                </a>
            </div>
        </div>

        <!-- CARD 1: Informasi Ruangan -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-2xl border border-gray-200 dark:border-gray-700/60 p-6 sm:p-8 mb-8">
            <div class="flex items-center gap-2.5 mb-6 pb-4 border-b border-gray-100 dark:border-gray-700/60">
                <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-brand-50 dark:bg-brand-500/10 text-brand-500">
                    <i class="fa-solid fa-pen-to-square text-sm"></i>
                </span>
                <div>
                    <h2 class="text-base font-bold text-gray-900 dark:text-white">Informasi & Spesifikasi Ruangan</h2>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Atur kode, nama, kapasitas, jam operasional, dan status ketersediaan.</p>
                </div>
            </div>

            <form action="{{ route('rooms.update', $room) }}" method="POST" id="edit-room-form">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Kode Ruangan <span class="text-red-500">*</span></label>
                        <input type="text" name="code" value="{{ old('code', $room->code) }}" class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 shadow-theme-xs" required>
                        @error('code') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nama Ruangan <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $room->name) }}" class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 shadow-theme-xs" required>
                        @error('name') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Kapasitas (Orang) <span class="text-red-500">*</span></label>
                        <input type="number" name="capacity" value="{{ old('capacity', $room->capacity) }}" class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 shadow-theme-xs" required>
                        @error('capacity') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Lokasi / Gedung <span class="text-red-500">*</span></label>
                        <input type="text" name="location" value="{{ old('location', $room->location) }}" class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 shadow-theme-xs" required>
                        @error('location') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Jam Buka Operasional <span class="text-red-500">*</span></label>
                        <input type="time" name="open_time" value="{{ old('open_time', substr($room->open_time, 0, 5)) }}" class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 shadow-theme-xs" required>
                        @error('open_time') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Jam Tutup Operasional <span class="text-red-500">*</span></label>
                        <input type="time" name="close_time" value="{{ old('close_time', substr($room->close_time, 0, 5)) }}" class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 shadow-theme-xs" required>
                        @error('close_time') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <x-common.toggle name="status" label="Status Ruangan" :checked="old('status', $room->status) === 'active'" valueOn="active" valueOff="inactive" />
                    </div>
                </div>
            </form>
        </div>

        <!-- CARD 2: Section Foto Ruangan (2 Kolom Interaktif) -->
        @php
            $photosData = $room->photos->map(fn($p, $index) => [
                'id' => $p->id,
                'url' => asset('storage/' . $p->photo_path),
                'delete_url' => route('rooms.photo.destroy', $p),
                'name' => 'Foto ' . ($index + 1),
                'sort_order' => $p->sort_order,
                'is_primary' => $index === 0,
            ])->values()->all();
            $maxPhotos = 6;
        @endphp

        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-2xl border border-gray-200 dark:border-gray-700/60 p-6 sm:p-8"
             x-data="{
                 photos: @js($photosData),
                 selectedIndex: 0,
                 deleteModalOpen: false,
                 photoToDelete: null,
                 lightboxOpen: false,
                 isUploading: false,
                 uploadError: '',

                 get selectedPhoto() {
                     if (this.photos.length === 0) return null;
                     if (this.selectedIndex >= this.photos.length) {
                         this.selectedIndex = 0;
                     }
                     return this.photos[this.selectedIndex];
                 },

                 selectPhoto(index) {
                     this.selectedIndex = index;
                 },

                 confirmDelete(photo) {
                     this.photoToDelete = photo;
                     this.deleteModalOpen = true;
                 },

                 closeDeleteModal() {
                     this.deleteModalOpen = false;
                     this.photoToDelete = null;
                 },

                 handleFileChange(event) {
                     const file = event.target.files[0];
                     if (!file) return;

                     const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
                     if (!allowedTypes.includes(file.type)) {
                         this.uploadError = 'Format file tidak didukung. Gunakan JPG, PNG, atau WebP.';
                         event.target.value = '';
                         return;
                     }

                     if (file.size > 5 * 1024 * 1024) {
                         this.uploadError = 'Ukuran file terlalu besar. Maksimal 5 MB per foto.';
                         event.target.value = '';
                         return;
                     }

                     this.uploadError = '';
                     this.isUploading = true;
                     event.target.form.submit();
                 }
             }">

            <!-- Section Header -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pb-5 mb-6 border-b border-gray-100 dark:border-gray-700/60">
                <div class="flex items-center gap-2.5">
                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-brand-50 dark:bg-brand-500/10 text-brand-500">
                        <i class="fa-solid fa-images text-sm"></i>
                    </span>
                    <div>
                        <h2 class="text-base font-bold text-gray-900 dark:text-white">Foto Ruangan</h2>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Kelola galeri visual ruangan (maksimal 6 foto). Klik foto mini di kanan untuk melihat preview besar di kiri.</p>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold"
                          :class="photos.length >= 6 ? 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400' : 'bg-brand-50 text-brand-700 dark:bg-brand-500/10 dark:text-brand-300'">
                        <i class="fa-solid fa-image text-xs"></i>
                        <span><span x-text="photos.length"></span> / {{ $maxPhotos }} Foto</span>
                    </span>
                </div>
            </div>

            <!-- Two Column Side-by-Side Split Layout: Left (Large Preview) & Right (Mini Previews + Upload + Guidelines) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8 items-start">

                <!-- ================= LEFT COLUMN: Large Preview of Selected Image ================= -->
                <div class="space-y-3 md:sticky md:top-6">
                    <div class="flex items-center justify-between">
                        <h3 class="text-sm font-semibold text-gray-800 dark:text-gray-200 flex items-center gap-2">
                            <i class="fa-regular fa-eye text-brand-500"></i>
                            <span>Preview Gambar Terpilih</span>
                        </h3>
                        <template x-if="selectedPhoto">
                            <span class="text-xs text-gray-500 dark:text-gray-400 font-medium"
                                  x-text="selectedIndex === 0 ? 'Foto Utama (#1)' : 'Foto #' + (selectedIndex + 1)"></span>
                        </template>
                    </div>

                    <!-- Main Large Preview Container (Proportional aspect ratio) -->
                    <div class="relative w-full rounded-2xl overflow-hidden bg-gray-950 border border-gray-200 dark:border-gray-700/80 shadow-md flex items-center justify-center aspect-[4/3] max-h-[360px] sm:max-h-[380px] group">

                        <!-- Photo Available State -->
                        <template x-if="selectedPhoto">
                            <div class="relative w-full h-full">
                                <img :src="selectedPhoto.url"
                                     :alt="'Preview ' + selectedPhoto.name"
                                     class="w-full h-full object-cover transition-all duration-300">

                                <!-- Ambient Top/Bottom Gradients -->
                                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-black/30 pointer-events-none"></div>

                                <!-- Top Overlay Badges -->
                                <div class="absolute top-3 inset-x-3 flex items-center justify-between gap-2">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold bg-black/60 text-white backdrop-blur-md shadow-xs">
                                        <i class="fa-solid fa-camera text-[10px]"></i>
                                        <span>Foto <span x-text="selectedIndex + 1"></span> dari <span x-text="photos.length"></span></span>
                                    </span>

                                    <button type="button"
                                            @click="lightboxOpen = true"
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold bg-white/20 hover:bg-white/30 text-white backdrop-blur-md transition-colors cursor-pointer shadow-xs"
                                            title="Perbesar Layar Penuh">
                                        <i class="fa-solid fa-expand text-xs"></i>
                                        <span class="hidden sm:inline">Perbesar</span>
                                    </button>
                                </div>

                                <!-- Bottom Info Caption -->
                                <div class="absolute bottom-3 inset-x-3 flex items-end justify-between gap-2 text-white">
                                    <div>
                                        <p class="text-xs font-medium text-gray-300 drop-shadow-xs">{{ $room->name }}</p>
                                        <p class="text-sm font-bold drop-shadow-sm" x-text="selectedPhoto.name"></p>
                                    </div>
                                    <template x-if="selectedIndex === 0">
                                        <span class="px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider bg-brand-500 text-white shadow-xs">
                                            Utama
                                        </span>
                                    </template>
                                </div>
                            </div>
                        </template>

                        <!-- Empty State (No Photos) -->
                        <template x-if="!selectedPhoto">
                            <div class="p-8 text-center flex flex-col items-center justify-center space-y-3 bg-gray-50 dark:bg-gray-900/50 w-full h-full">
                                <div class="w-16 h-16 rounded-2xl bg-gray-100 dark:bg-gray-800 flex items-center justify-center text-gray-400 dark:text-gray-500 shadow-inner">
                                    <i class="fa-regular fa-image text-3xl"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-700 dark:text-gray-300">Belum Ada Foto Ruangan</p>
                                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1 max-w-xs">Gunakan form di sebelah kanan untuk menambahkan foto (maksimal 6 foto).</p>
                                </div>
                            </div>
                        </template>
                    </div>

                    <p class="text-xs text-gray-500 dark:text-gray-400 text-center sm:text-left flex items-center gap-1.5">
                        <i class="fa-solid fa-circle-info text-brand-500"></i>
                        <span>Foto pertama (#1) akan menjadi foto utama pada kartu dan katalog pencarian ruangan.</span>
                    </p>
                </div>

                <!-- ================= RIGHT COLUMN: Mini Previews + Upload Form + Composition Guide ================= -->
                <div class="space-y-5">

                    <!-- Sub-section: Mini Preview Gallery (Grid max 6) -->
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="text-sm font-semibold text-gray-800 dark:text-gray-200 flex items-center gap-2">
                                <i class="fa-solid fa-grip text-brand-500"></i>
                                <span>Galeri Mini Preview (Maksimal 6 Foto)</span>
                            </h3>
                            <span class="text-xs text-gray-400 dark:text-gray-500">Klik untuk melihat preview</span>
                        </div>

                        <!-- Mini Previews Grid (Responsive 3 columns) -->
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                            <!-- Loop Existing Photos -->
                            <template x-for="(photo, index) in photos" :key="photo.id">
                                <div class="relative group rounded-xl overflow-hidden border transition-all duration-200 cursor-pointer aspect-[4/3] bg-gray-100 dark:bg-gray-900"
                                     :class="selectedIndex === index ? 'ring-3 ring-brand-500 border-brand-500 shadow-md scale-[1.02]' : 'border-gray-200 dark:border-gray-700 hover:border-brand-300 hover:shadow-xs'"
                                     @click="selectPhoto(index)">

                                    <!-- Thumbnail Image -->
                                    <img :src="photo.url"
                                         :alt="photo.name"
                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">

                                    <!-- Dark Overlay on Hover -->
                                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none"></div>

                                    <!-- Top-Left Index Pill -->
                                    <span class="absolute top-1.5 left-1.5 px-2 py-0.5 rounded-md text-[11px] font-bold text-white shadow-xs backdrop-blur-xs z-10"
                                          :class="index === 0 ? 'bg-brand-500' : 'bg-black/60'">
                                        #<span x-text="index + 1"></span>
                                    </span>

                                    <!-- Active Selection Indicator -->
                                    <template x-if="selectedIndex === index">
                                        <span class="absolute bottom-1.5 left-1.5 inline-flex items-center gap-1 px-1.5 py-0.5 rounded bg-brand-500 text-white text-[10px] font-semibold shadow-xs z-10">
                                            <i class="fa-solid fa-check text-[9px]"></i> Terpilih
                                        </span>
                                    </template>

                                    <!-- Delete Button (Icon fa delete di pojok kanan atas, muncul saat hover) -->
                                    <button type="button"
                                            @click.stop="confirmDelete(photo)"
                                            class="absolute top-2 right-2 w-7 h-7 rounded-full bg-red-600 hover:bg-red-700 active:bg-red-800 text-white flex items-center justify-center shadow-lg opacity-0 group-hover:opacity-100 transition-all duration-200 transform scale-75 group-hover:scale-100 hover:!scale-110 focus:outline-hidden z-20 cursor-pointer"
                                            title="Hapus foto ini"
                                            aria-label="Hapus foto ini">
                                        <i class="fa-solid fa-trash-can text-xs"></i>
                                    </button>
                                </div>
                            </template>

                            <!-- Empty Slot Placeholders (Fill up to 6 slots) -->
                            <template x-for="slotIndex in (6 - photos.length)" :key="'empty-slot-' + slotIndex">
                                <div class="border-2 border-dashed border-gray-200 dark:border-gray-700 rounded-xl aspect-[4/3] flex flex-col items-center justify-center p-3 text-center bg-gray-50/50 dark:bg-gray-900/30">
                                    <i class="fa-regular fa-image text-xl text-gray-300 dark:text-gray-600 mb-1"></i>
                                    <span class="text-[11px] font-medium text-gray-400 dark:text-gray-500">Slot Kosong</span>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Sub-section: Upload Form (Only appears if total photos < 6) -->
                    <template x-if="photos.length < 6">
                        <div class="pt-4 border-t border-gray-100 dark:border-gray-700/60">
                            <div class="flex items-center justify-between mb-2">
                                <label class="block text-sm font-semibold text-gray-800 dark:text-gray-200">
                                    Unggah Foto Ruangan Baru
                                </label>
                                <span class="text-xs text-brand-600 dark:text-brand-400 font-medium">
                                    Tersisa <strong x-text="6 - photos.length"></strong> dari 6 slot
                                </span>
                            </div>

                            <form action="{{ route('rooms.photo.store', $room) }}"
                                  method="POST"
                                  enctype="multipart/form-data"
                                  id="upload-room-photo-form"
                                  class="space-y-3">
                                @csrf

                                <!-- Dropzone Upload Area -->
                                <label for="room-photo-input"
                                       class="relative flex flex-col items-center justify-center p-6 border-2 border-dashed border-brand-200 dark:border-brand-900/60 hover:border-brand-500 dark:hover:border-brand-500 rounded-2xl bg-brand-50/30 dark:bg-brand-950/20 hover:bg-brand-50/60 dark:hover:bg-brand-950/40 transition-all cursor-pointer group text-center">

                                    <!-- Upload State Icon & Content -->
                                    <div x-show="!isUploading" class="flex flex-col items-center">
                                        <div class="w-12 h-12 rounded-full bg-brand-100 dark:bg-brand-500/20 text-brand-600 dark:text-brand-400 flex items-center justify-center mb-2.5 group-hover:scale-110 transition-transform">
                                            <i class="fa-solid fa-cloud-arrow-up text-xl"></i>
                                        </div>
                                        <p class="text-sm font-semibold text-gray-800 dark:text-gray-200">
                                            <span class="text-brand-600 dark:text-brand-400 underline decoration-brand-300 underline-offset-2">Klik untuk memilih foto</span> atau seret file ke sini
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                            Mendukung JPG, JPEG, PNG, atau WebP (Maksimal 5 MB per file)
                                        </p>
                                    </div>

                                    <!-- Loading Spinner when Uploading -->
                                    <div x-show="isUploading" class="flex flex-col items-center py-2" style="display: none;">
                                        <div class="w-8 h-8 border-3 border-brand-500 border-t-transparent rounded-full animate-spin mb-2"></div>
                                        <p class="text-sm font-medium text-brand-600 dark:text-brand-400">Mengunggah foto...</p>
                                    </div>

                                    <input type="file"
                                           name="photo"
                                           id="room-photo-input"
                                           accept="image/jpeg,image/png,image/jpg,image/webp"
                                           class="hidden"
                                           :disabled="isUploading"
                                           @change="handleFileChange($event)">
                                </label>

                                <!-- Error Alert if any -->
                                <p x-show="uploadError" x-text="uploadError" class="text-red-500 text-xs font-medium" style="display: none;"></p>
                                @error('photo')
                                    <p class="text-red-500 text-xs font-medium">{{ $message }}</p>
                                @enderror
                            </form>
                        </div>
                    </template>

                    <!-- Limit Reached Notice (If photos count == 6) -->
                    <template x-if="photos.length >= 6">
                        <div class="p-4 rounded-xl bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-800/50 flex items-start gap-3">
                            <div class="mt-0.5 flex-shrink-0 w-8 h-8 rounded-full bg-amber-100 dark:bg-amber-900/40 text-amber-600 dark:text-amber-400 flex items-center justify-center">
                                <i class="fa-solid fa-circle-check text-sm"></i>
                            </div>
                            <div class="text-sm">
                                <p class="font-bold text-amber-900 dark:text-amber-200">Kuota Maksimal 6 Foto Terpenuhi</p>
                                <p class="text-xs text-amber-700 dark:text-amber-300/90 mt-0.5 leading-relaxed">
                                    Ruangan ini telah memiliki 6 foto (batas maksimal). Untuk mengunggah foto baru, silakan hapus salah satu foto pada galeri di atas terlebih dahulu.
                                </p>
                            </div>
                        </div>
                    </template>

                    <!-- Sub-section: Rekomendasi Komposisi Gambar -->
                    {{-- <div class="rounded-2xl bg-gradient-to-br from-gray-50 to-brand-50/20 dark:from-gray-900/60 dark:to-brand-950/20 border border-gray-200/90 dark:border-gray-700/60 p-4 sm:p-5">
                        <div class="flex items-center gap-2 mb-3">
                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-md bg-brand-100 dark:bg-brand-500/20 text-brand-600 dark:text-brand-400">
                                <i class="fa-solid fa-lightbulb text-xs"></i>
                            </span>
                            <h4 class="text-xs sm:text-sm font-bold text-gray-800 dark:text-gray-200">Rekomendasi Komposisi & Visual Foto Ruangan</h4>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 text-xs text-gray-600 dark:text-gray-300">
                            <!-- Tip 1 -->
                            <div class="flex items-start gap-2 p-2 rounded-lg bg-white/70 dark:bg-gray-800/60 border border-gray-100 dark:border-gray-700/50">
                                <i class="fa-solid fa-vector-square text-brand-500 mt-0.5 shrink-0"></i>
                                <div>
                                    <strong class="text-gray-800 dark:text-gray-200">Sudut Lebar (Wide Shot):</strong>
                                    <p class="text-gray-500 dark:text-gray-400 text-[11px] mt-0.5">Ambil dari sudut/diagonal ruangan untuk memperlihatkan luas keseluruhan ruang.</p>
                                </div>
                            </div>

                            <!-- Tip 2 -->
                            <div class="flex items-start gap-2 p-2 rounded-lg bg-white/70 dark:bg-gray-800/60 border border-gray-100 dark:border-gray-700/50">
                                <i class="fa-solid fa-chair text-brand-500 mt-0.5 shrink-0"></i>
                                <div>
                                    <strong class="text-gray-800 dark:text-gray-200">Tata Letak Meja & Kursi:</strong>
                                    <p class="text-gray-500 dark:text-gray-400 text-[11px] mt-0.5">Pastikan susunan kursi dan meja tertata rapi sesuai kapasitas maksimal.</p>
                                </div>
                            </div>

                            <!-- Tip 3 -->
                            <div class="flex items-start gap-2 p-2 rounded-lg bg-white/70 dark:bg-gray-800/60 border border-gray-100 dark:border-gray-700/50">
                                <i class="fa-solid fa-display text-brand-500 mt-0.5 shrink-0"></i>
                                <div>
                                    <strong class="text-gray-800 dark:text-gray-200">Fasilitas Utama:</strong>
                                    <p class="text-gray-500 dark:text-gray-400 text-[11px] mt-0.5">Sertakan foto fokus proyektor, podium, AC, papan tulis, atau sound system.</p>
                                </div>
                            </div>

                            <!-- Tip 4 -->
                            <div class="flex items-start gap-2 p-2 rounded-lg bg-white/70 dark:bg-gray-800/60 border border-gray-100 dark:border-gray-700/50">
                                <i class="fa-solid fa-sun text-brand-500 mt-0.5 shrink-0"></i>
                                <div>
                                    <strong class="text-gray-800 dark:text-gray-200">Pencahayaan Terang:</strong>
                                    <p class="text-gray-500 dark:text-gray-400 text-[11px] mt-0.5">Buka tirai jendela & nyalakan lampu agar ruangan tampak bersih dan terang.</p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-3 pt-2.5 border-t border-gray-200/60 dark:border-gray-700/40 flex flex-wrap items-center gap-x-4 gap-y-1 text-[11px] text-gray-500 dark:text-gray-400">
                            <span class="inline-flex items-center gap-1.5"><i class="fa-solid fa-check text-green-500 text-[10px]"></i> Orientasi landscape (horizontal)</span>
                            <span class="inline-flex items-center gap-1.5"><i class="fa-solid fa-check text-green-500 text-[10px]"></i> Rasio rekomendasi 3:2 atau 16:9</span>
                            <span class="inline-flex items-center gap-1.5"><i class="fa-solid fa-check text-green-500 text-[10px]"></i> Maksimal 5 MB per file</span>
                        </div>
                    </div> --}}

                </div>
            </div>

            <!-- ================= MODAL KONFIRMASI PENGHAPUSAN FOTO ================= -->
            <div x-show="deleteModalOpen"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 z-[99999] overflow-y-auto"
                 style="display: none;"
                 @keydown.escape.window="closeDeleteModal()">

                <!-- Backdrop Overlay -->
                <div class="fixed inset-0 bg-gray-900/60 dark:bg-gray-950/80 backdrop-blur-xs transition-opacity"
                     @click="closeDeleteModal()"></div>

                <!-- Modal Dialog -->
                <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                    <div x-show="deleteModalOpen"
                         x-transition:enter="ease-out duration-300"
                         x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                         x-transition:leave="ease-in duration-200"
                         x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                         x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                         @click.stop
                         class="relative transform overflow-hidden rounded-2xl bg-white dark:bg-gray-800 text-left shadow-2xl transition-all w-full sm:max-w-md border border-gray-100 dark:border-gray-700">

                        <!-- Modal Header -->
                        <div class="flex items-center justify-between bg-red-600 text-white px-6 py-4">
                            <div class="flex items-center gap-3">
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-white/20 text-white">
                                    <i class="fa-solid fa-triangle-exclamation text-base"></i>
                                </span>
                                <h3 class="text-base font-bold text-white">Konfirmasi Hapus Foto</h3>
                            </div>
                            <button @click="closeDeleteModal()"
                                    type="button"
                                    class="text-white/80 hover:text-white hover:bg-white/15 p-1.5 rounded-lg transition-colors cursor-pointer"
                                    aria-label="Tutup">
                                <i class="fa-solid fa-xmark text-lg"></i>
                            </button>
                        </div>

                        <!-- Modal Body -->
                        <div class="px-6 py-5 space-y-4">
                            <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed">
                                Apakah Anda yakin ingin menghapus foto ini dari galeri ruangan? Foto yang dihapus tidak dapat dipulihkan kembali.
                            </p>

                            <!-- Photo Preview to Delete -->
                            <template x-if="photoToDelete">
                                <div class="rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700 bg-gray-900 aspect-video relative shadow-inner">
                                    <img :src="photoToDelete.url" :alt="photoToDelete.name" class="w-full h-full object-cover">
                                    <div class="absolute bottom-2 left-2 bg-black/60 backdrop-blur-xs text-white text-xs font-semibold px-2 py-0.5 rounded">
                                        <span x-text="photoToDelete.name"></span>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <!-- Modal Footer -->
                        <div class="border-t border-gray-100 dark:border-gray-700/60 bg-gray-50 dark:bg-gray-800/50 px-6 py-3.5 flex items-center justify-end gap-3">
                            <button type="button"
                                    @click="closeDeleteModal()"
                                    class="px-4 py-2 rounded-lg text-sm font-medium bg-gray-100 hover:bg-gray-200 text-gray-700 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600 transition-colors">
                                Batal
                            </button>

                            <template x-if="photoToDelete">
                                <form :action="photoToDelete.delete_url" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="inline-flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors shadow-xs">
                                        <i class="fa-solid fa-trash-can text-xs"></i>
                                        <span>Ya, Hapus Foto</span>
                                    </button>
                                </form>
                            </template>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ================= LIGHTBOX MODAL (FULLSCREEN PREVIEW) ================= -->
            <div x-show="lightboxOpen && selectedPhoto"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 z-[99999] bg-black/95 backdrop-blur-md flex flex-col justify-between p-4 sm:p-6"
                 style="display: none;"
                 @keydown.escape.window="lightboxOpen = false">

                <!-- Top Bar -->
                <div class="flex items-center justify-between text-white z-10">
                    <div class="flex items-center gap-3">
                        <span class="text-sm font-medium text-gray-300">{{ $room->name }} &mdash; <span x-text="selectedPhoto?.name"></span></span>
                    </div>
                    <button type="button" @click="lightboxOpen = false" class="w-10 h-10 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition-colors">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>

                <!-- Center Image Stage -->
                <div class="relative flex-1 flex items-center justify-center my-4 overflow-hidden" @click.self="lightboxOpen = false">
                    <img :src="selectedPhoto?.url" :alt="selectedPhoto?.name" class="max-h-[80vh] max-w-full object-contain rounded-xl shadow-2xl transition-all duration-300">
                </div>

                <!-- Bottom Thumbnails Bar -->
                <div class="flex items-center justify-center gap-2 overflow-x-auto py-2 z-10">
                    <template x-for="(photo, index) in photos" :key="'lb-' + photo.id">
                        <button type="button" @click="selectPhoto(index)" class="w-16 h-12 rounded-lg overflow-hidden shrink-0 transition-all" :class="selectedIndex === index ? 'ring-2 ring-brand-400 scale-105 opacity-100' : 'opacity-50 hover:opacity-100'">
                            <img :src="photo.url" :alt="photo.name" class="w-full h-full object-cover">
                        </button>
                    </template>
                </div>
            </div>

        </div>

        <!-- CARD 3: Section Aksi (Simpan Perubahan & Batal) di Paling Bawah -->
        <div class="mt-8 bg-white dark:bg-gray-800 shadow-sm rounded-2xl border border-gray-200 dark:border-gray-700/60 p-4 sm:p-6 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-2.5 text-xs sm:text-sm text-gray-500 dark:text-gray-400">
                <i class="fa-solid fa-circle-info text-brand-500 text-base"></i>
                <span>Pastikan data spesifikasi dan perubahan foto ruangan telah sesuai sebelum menyimpan.</span>
            </div>
            <div class="flex items-center gap-3 w-full sm:w-auto justify-end">
                <a href="{{ route('rooms.index') }}" class="inline-flex items-center justify-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 px-5 py-2.5 rounded-xl text-sm font-medium transition-colors dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600">
                    <i class="fa-solid fa-arrow-left text-sm"></i>
                    <span>Batal</span>
                </a>
                <button type="submit" form="edit-room-form" class="inline-flex items-center justify-center gap-2 bg-brand-500 hover:bg-brand-600 active:bg-brand-700 text-white px-6 py-2.5 rounded-xl text-sm font-semibold shadow-theme-xs transition-colors cursor-pointer">
                    <i class="fa-solid fa-floppy-disk text-sm"></i>
                    <span>Simpan Perubahan</span>
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
