@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">Pengajuan Baru</h1>

        <div class="bg-white shadow rounded-lg p-6">
            <form action="{{ route('bookings.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Ruangan</label>
                        <select name="room_id" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-brand-500 focus:border-brand-500" required>
                            <option value="">Pilih Ruangan</option>
                            @foreach($rooms as $room)
                            <option value="{{ $room->id }}">{{ $room->name }} - {{ $room->code }}</option>
                            @endforeach
                        </select>
                        @error('room_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                        <input type="date" name="booking_date" value="{{ old('booking_date') }}" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-brand-500 focus:border-brand-500" required>
                        @error('booking_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jam Mulai</label>
                        <input type="time" name="start_time" value="{{ old('start_time') }}" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-brand-500 focus:border-brand-500" required>
                        @error('start_time') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jam Selesai</label>
                        <input type="time" name="end_time" value="{{ old('end_time') }}" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-brand-500 focus:border-brand-500" required>
                        @error('end_time') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Kegiatan</label>
                        <input type="text" name="activity_name" value="{{ old('activity_name') }}" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-brand-500 focus:border-brand-500" required>
                        @error('activity_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tujuan Kegiatan</label>
                        <textarea name="purpose" rows="3" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-brand-500 focus:border-brand-500" required>{{ old('purpose') }}</textarea>
                        @error('purpose') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Peserta</label>
                        <input type="number" name="participant_count" value="{{ old('participant_count') }}" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-brand-500 focus:border-brand-500" required>
                        @error('participant_count') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Penanggung Jawab</label>
                        <input type="text" name="person_in_charge" value="{{ old('person_in_charge') }}" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-brand-500 focus:border-brand-500" required>
                        @error('person_in_charge') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">No. Telepon</label>
                        <input type="text" name="contact_phone" value="{{ old('contact_phone') }}" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-brand-500 focus:border-brand-500" required>
                        @error('contact_phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Dokumen (PDF)</label>
                        <input type="file" name="document" accept=".pdf" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-brand-500 focus:border-brand-500">
                        @error('document') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <a href="{{ route('bookings.index') }}" class="inline-flex items-center gap-2 bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium transition-colors dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600">
                        <i class="fa-solid fa-arrow-left text-sm"></i>
                        <span>Batal</span>
                    </a>
                    <button type="submit" class="inline-flex items-center gap-2 bg-brand-500 hover:bg-brand-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                        <i class="fa-solid fa-paper-plane text-sm"></i>
                        <span>Kirim Pengajuan</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
