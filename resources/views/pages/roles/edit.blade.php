@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="flex items-center gap-2 text-sm text-gray-500 mb-6">
            <a href="{{ route('dashboard') }}" class="hover:text-brand-500 transition-colors"><i class="fa-solid fa-house"></i></a>
            <i class="fa-solid fa-chevron-right text-xs"></i>
            <a href="{{ route('roles.index') }}" class="hover:text-brand-500 transition-colors">Role</a>
            <i class="fa-solid fa-chevron-right text-xs"></i>
            <span class="text-gray-900 font-medium">Edit</span>
        </nav>

        <h1 class="text-2xl font-bold text-gray-900 mb-6">Edit Role</h1>

        <div class="bg-white shadow rounded-lg p-6">
            <form action="{{ route('roles.update', $role) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Role</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $role->name) }}" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-brand-500 focus:border-brand-500" required>
                        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                        <textarea name="description" rows="3" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-brand-500 focus:border-brand-500">{{ old('description', $role->description) }}</textarea>
                        @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $role->is_active) ? 'checked' : '' }} class="rounded border-gray-300 text-brand-500 shadow-sm focus:ring-brand-500">
                            <span class="ml-2 text-sm text-gray-700">Status Role Aktif</span>
                        </label>
                        @error('is_active') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="mt-6 flex flex-wrap items-center justify-between gap-4 border-t pt-6">
                    <div>
                        @if($role->is_active)
                            <button type="button" @click="$dispatch('open-modal', 'confirm-deactivate-role')" class="inline-flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                                <i class="fa-solid fa-ban text-sm"></i>
                                <span>Nonaktifkan Role</span>
                            </button>
                        @endif
                    </div>
                    <div class="flex items-center gap-3">
                        <a href="{{ route('roles.index') }}" class="inline-flex items-center gap-2 bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium transition-colors dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600">
                            <i class="fa-solid fa-arrow-left text-sm"></i>
                            <span>Batal</span>
                        </a>
                        <button type="submit" class="inline-flex items-center gap-2 bg-brand-500 hover:bg-brand-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                            <i class="fa-solid fa-floppy-disk text-sm"></i>
                            <span>Simpan Perubahan</span>
                        </button>
                    </div>
                </div>
            </form>

            @if($role->is_active)
                <form id="deactivate-role-form" action="{{ route('roles.destroy', $role) }}" method="POST" class="hidden">
                    @csrf
                    @method('DELETE')
                </form>

                <!-- Modal Konfirmasi Nonaktifkan Role -->
                <x-common.modal id="confirm-deactivate-role" title="Konfirmasi Nonaktifkan Role" icon="fa-solid fa-triangle-exclamation" maxWidth="md">
                    <p class="text-gray-600 dark:text-gray-300">
                        Apakah Anda yakin ingin menonaktifkan role <strong>{{ $role->name }}</strong>? Role yang dinonaktifkan tidak dapat dipilih untuk pengguna baru.
                    </p>

                    <x-slot:footer>
                        <button type="button" @click="close()" class="inline-flex items-center gap-2 bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium transition-colors dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600">
                            <i class="fa-solid fa-xmark text-sm"></i>
                            <span>Batal</span>
                        </button>
                        <button type="button" @click="document.getElementById('deactivate-role-form').submit()" class="inline-flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                            <i class="fa-solid fa-ban text-sm"></i>
                            <span>Ya, Nonaktifkan</span>
                        </button>
                    </x-slot:footer>
                </x-common.modal>
            @endif
        </div>
    </div>
</div>
@endsection
