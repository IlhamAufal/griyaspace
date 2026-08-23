@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="flex items-center gap-2 text-sm text-gray-500 mb-6">
            <a href="{{ route('dashboard') }}" class="hover:text-blue-600 transition-colors"><i class="fa-solid fa-house"></i></a>
            <i class="fa-solid fa-chevron-right text-xs"></i>
            <a href="{{ route('roles.index') }}" class="hover:text-blue-600 transition-colors">Role</a>
            <i class="fa-solid fa-chevron-right text-xs"></i>
            <span class="text-gray-900 font-medium">{{ $role->name }}</span>
        </nav>

        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Detail Role: {{ $role->name }}</h1>
            <div class="flex items-center gap-2">
                <a href="{{ route('roles.index') }}" class="inline-flex items-center gap-2 bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium transition-colors dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600">
                    <i class="fa-solid fa-arrow-left text-sm"></i>
                    <span>Kembali</span>
                </a>
                <a href="{{ route('roles.edit', $role) }}" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                    <i class="fa-solid fa-pen-to-square text-sm"></i>
                    <span>Edit Role</span>
                </a>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-800 dark:text-gray-200">Nama Role</label>
                    <p class="mt-1 text-base font-normal text-gray-600 dark:text-gray-300">{{ $role->name }}</p>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-800 dark:text-gray-200">Jumlah Pengguna</label>
                    <p class="mt-1 text-base font-normal text-gray-600 dark:text-gray-300">{{ $role->users->count() }} Pengguna</p>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-800 dark:text-gray-200">Deskripsi</label>
                    <p class="mt-1 text-sm font-normal text-gray-600 dark:text-gray-300">{{ $role->description ?: '-' }}</p>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-800 dark:text-gray-200">Status</label>
                    <p class="mt-1">
                        @if($role->is_active)
                            <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">Aktif</span>
                        @else
                            <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">Nonaktif</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>

        @if($role->users->count() > 0)
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Daftar Pengguna dengan Role ini</h2>
                </div>
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-brand-500 text-white">
                        <tr>
                            <th class="px-6 py-3.5 text-left text-xs font-bold text-white uppercase tracking-wider">Nama</th>
                            <th class="px-6 py-3.5 text-left text-xs font-bold text-white uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3.5 text-left text-xs font-bold text-white uppercase tracking-wider">Organisasi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($role->users as $u)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">{{ $u->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $u->email }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $u->organization->name ?? '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
