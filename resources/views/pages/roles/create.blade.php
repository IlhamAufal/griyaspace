@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="flex items-center gap-2 text-sm text-gray-500 mb-6">
            <a href="{{ route('dashboard') }}" class="hover:text-brand-500 transition-colors"><i class="fa-solid fa-house"></i></a>
            <i class="fa-solid fa-chevron-right text-xs"></i>
            <a href="{{ route('roles.index') }}" class="hover:text-brand-500 transition-colors">Role</a>
            <i class="fa-solid fa-chevron-right text-xs"></i>
            <span class="text-gray-900 font-medium">Tambah</span>
        </nav>

        <h1 class="text-2xl font-bold text-gray-900 mb-6">Tambah Role</h1>

        <div class="bg-white shadow rounded-lg p-6">
            <form action="{{ route('roles.store') }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Role</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" placeholder="Contoh: Staff Keuangan" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-brand-500 focus:border-brand-500" required>
                        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                        <textarea name="description" rows="3" placeholder="Deskripsi tugas atau wewenang role..." class="w-full border-gray-300 rounded-md shadow-sm focus:ring-brand-500 focus:border-brand-500">{{ old('description') }}</textarea>
                        @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', 1) ? 'checked' : '' }} class="rounded border-gray-300 text-brand-500 shadow-sm focus:ring-brand-500">
                            <span class="ml-2 text-sm text-gray-700">Status Role Aktif</span>
                        </label>
                        @error('is_active') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <a href="{{ route('roles.index') }}" class="inline-flex items-center gap-2 bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium transition-colors dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600">
                        <i class="fa-solid fa-arrow-left text-sm"></i>
                        <span>Batal</span>
                    </a>
                    <button type="submit" class="inline-flex items-center gap-2 bg-brand-500 hover:bg-brand-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                        <i class="fa-solid fa-floppy-disk text-sm"></i>
                        <span>Simpan</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
