@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">Edit Pengajuan</h1>

        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-2xl border border-gray-200 dark:border-gray-700/60 p-6 sm:p-8">
            <form action="{{ route('bookings.update', $booking) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Ruangan <span class="text-red-500">*</span></label>
                        <select name="room_id" class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 shadow-theme-xs" required>
                            <option value="">Pilih Ruangan</option>
                            @foreach($rooms as $room)
                            <option value="{{ $room->id }}" {{ old('room_id', $booking->room_id) == $room->id ? 'selected' : '' }}>{{ $room->name }} - {{ $room->code }}</option>
                            @endforeach
                        </select>
                        @error('room_id') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tanggal Kegiatan <span class="text-red-500">*</span></label>
                        <input type="date" name="booking_date" value="{{ old('booking_date', $booking->booking_date->format('Y-m-d')) }}" min="{{ date('Y-m-d') }}" class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 shadow-theme-xs" required>
                        @error('booking_date') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Jam Mulai <span class="text-red-500">*</span></label>
                        <input type="time" name="start_time" value="{{ old('start_time', substr($booking->start_time, 0, 5)) }}" class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 shadow-theme-xs" required>
                        @error('start_time') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Jam Selesai <span class="text-red-500">*</span></label>
                        <input type="time" name="end_time" value="{{ old('end_time', substr($booking->end_time, 0, 5)) }}" class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 shadow-theme-xs" required>
                        @error('end_time') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nama Kegiatan <span class="text-red-500">*</span></label>
                        <input type="text" name="activity_name" value="{{ old('activity_name', $booking->activity_name) }}" class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 shadow-theme-xs" required>
                        @error('activity_name') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tujuan Kegiatan <span class="text-red-500">*</span></label>
                        <textarea name="purpose" rows="3" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-3 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 shadow-theme-xs" required>{{ old('purpose', $booking->purpose) }}</textarea>
                        @error('purpose') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Jumlah Peserta <span class="text-red-500">*</span></label>
                        <input type="number" name="participant_count" value="{{ old('participant_count', $booking->participant_count) }}" class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 shadow-theme-xs" required>
                        @error('participant_count') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Penanggung Jawab <span class="text-red-500">*</span></label>
                        <input type="text" name="person_in_charge" value="{{ old('person_in_charge', $booking->person_in_charge) }}" class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 shadow-theme-xs" required>
                        @error('person_in_charge') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">No. Telepon / WhatsApp <span class="text-red-500">*</span></label>
                        <input type="text" name="contact_phone" value="{{ old('contact_phone', $booking->contact_phone) }}" class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 shadow-theme-xs" required>
                        @error('contact_phone') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Dokumen Surat Permohonan (PDF)</label>
                        <input type="file" name="document" accept=".pdf" class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 file:mr-4 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100 dark:file:bg-brand-900/30 dark:file:text-brand-300 shadow-theme-xs">
                        @if($booking->document_path)
                        <p class="mt-1.5 text-xs text-gray-500 dark:text-gray-400">Dokumen saat ini: <a href="{{ Storage::url($booking->document_path) }}" target="_blank" class="text-brand-500 font-medium hover:underline inline-flex items-center gap-1"><i class="fa-solid fa-file-pdf"></i> Lihat Dokumen</a></p>
                        @endif
                        @error('document') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-gray-100 dark:border-gray-700/60 flex items-center justify-end gap-3">
                    <a href="{{ route('bookings.show', $booking) }}" class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 px-5 py-2.5 rounded-lg text-sm font-medium transition-colors dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600">
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
