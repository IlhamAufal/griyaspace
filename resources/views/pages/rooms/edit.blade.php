@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">Edit Ruangan</h1>

        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-2xl border border-gray-200 dark:border-gray-700/60 p-6 sm:p-8">
            <form action="{{ route('rooms.update', $room) }}" method="POST">
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

                <div class="mt-8 pt-6 border-t border-gray-100 dark:border-gray-700/60">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">Foto Ruangan</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Kelola foto ruangan. Foto yang diunggah akan ditampilkan di halaman detail ruangan.</p>

                    <div id="photo-grid" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 mb-4">
                        @forelse($room->photos as $photo)
                            <div class="relative group rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900" data-photo-id="{{ $photo->id }}">
                                <img src="{{ asset('storage/' . $photo->photo_path) }}" alt="Foto Ruangan" class="w-full h-32 object-cover">
                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                    <form action="{{ route('rooms.photo.destroy', $photo) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus foto ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white rounded-lg px-3 py-1.5 text-xs font-medium transition-colors inline-flex items-center gap-1.5">
                                            <i class="fa-solid fa-trash-can text-[10px]"></i>
                                            <span>Hapus</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full flex flex-col items-center justify-center py-8 border-2 border-dashed border-gray-200 dark:border-gray-700 rounded-lg">
                                <i class="fa-regular fa-image text-3xl text-gray-300 dark:text-gray-600 mb-2"></i>
                                <p class="text-sm text-gray-400 dark:text-gray-500">Belum ada foto</p>
                            </div>
                        @endforelse
                    </div>

                    <form action="{{ route('rooms.photo.store', $room) }}" method="POST" enctype="multipart/form-data" id="add-photo-form">
                        @csrf
                        <label for="photo-input" class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2.5 rounded-lg text-sm font-medium cursor-pointer transition-colors dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600">
                            <i class="fa-solid fa-cloud-arrow-up text-sm"></i>
                            <span>Tambah Foto</span>
                        </label>
                        <input type="file" name="photo" id="photo-input" accept="image/jpeg,image/png,image/webp" class="hidden" onchange="this.form.submit();">
                        @error('photo') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                    </form>

                    <div class="mt-4 rounded-lg bg-gray-50 dark:bg-gray-900/50 border border-gray-200 dark:border-gray-700 p-4">
                        <div class="flex items-start gap-3">
                            <div class="mt-0.5 flex-shrink-0 w-8 h-8 rounded-full bg-brand-50 dark:bg-brand-500/10 flex items-center justify-center">
                                <i class="fa-solid fa-circle-info text-brand-500 text-sm"></i>
                            </div>
                            <div class="text-sm space-y-1.5">
                                <p class="font-medium text-gray-700 dark:text-gray-300">Persyaratan Foto</p>
                                <ul class="text-gray-500 dark:text-gray-400 space-y-1 list-none">
                                    <li class="flex items-center gap-2"><i class="fa-solid fa-check text-[10px] text-green-500"></i> <strong>Format:</strong>&nbsp;JPG, JPEG, PNG, atau WebP</li>
                                    <li class="flex items-center gap-2"><i class="fa-solid fa-check text-[10px] text-green-500"></i> <strong>Ukuran file:</strong>&nbsp;maksimal 5 MB per foto</li>
                                    <li class="flex items-center gap-2"><i class="fa-solid fa-check text-[10px] text-green-500"></i> <strong>Resolusi rekomendasi:</strong>&nbsp;1200 &times; 800 px (rasio 3:2)</li>
                                    <li class="flex items-center gap-2"><i class="fa-solid fa-check text-[10px] text-green-500"></i> <strong>Orientasi:</strong>&nbsp;landscape (horizontal) untuk hasil terbaik</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-gray-100 dark:border-gray-700/60 flex items-center justify-end gap-3">
                    <a href="{{ route('rooms.index') }}" class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 px-5 py-2.5 rounded-lg text-sm font-medium transition-colors dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600">
                        <i class="fa-solid fa-arrow-left text-sm"></i>
                        <span>Batal</span>
                    </a>
                    <button type="submit" class="inline-flex items-center gap-2 bg-brand-500 hover:bg-brand-600 text-white px-5 py-2.5 rounded-lg text-sm font-medium shadow-theme-xs transition-colors">
                        <i class="fa-solid fa-floppy-disk text-sm"></i>
                        <span>Simpan Perubahan</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
