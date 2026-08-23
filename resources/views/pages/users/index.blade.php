@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="flex items-center gap-2 text-sm text-gray-500 mb-6">
            <a href="{{ route('dashboard') }}" class="hover:text-brand-500 transition-colors"><i class="fa-solid fa-house"></i></a>
            <i class="fa-solid fa-chevron-right text-xs"></i>
            <span class="text-gray-900 font-medium">Pengguna</span>
        </nav>

        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Daftar Pengguna</h1>
            <a href="{{ route('users.create') }}" class="bg-accent-500 hover:bg-accent-600 text-white px-4 py-2 rounded-lg inline-flex items-center text-sm font-medium shadow-theme-xs transition-colors">
                <span>Tambah Pengguna Baru</span>
            </a>
        </div>

        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-200 dark:border-gray-700/60 p-4 mb-5">
            <form action="{{ route('users.index') }}" method="GET" class="flex flex-wrap items-end gap-4">
                <div class="flex-1 min-w-[220px]">
                    <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">Pencarian</label>
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama, email, atau username..." class="h-10 w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm bg-transparent placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 shadow-theme-xs">
                        <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                    </div>
                </div>
                <div class="w-48">
                    <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">Role</label>
                    <select name="role_id" class="h-10 w-full border border-gray-300 rounded-lg text-sm px-3 py-2 bg-transparent focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 shadow-theme-xs">
                        <option value="">Semua Role</option>
                        @foreach($roles as $role)
                        <option value="{{ $role->id }}" {{ request('role_id') == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="w-44">
                    <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">Status</label>
                    <select name="status" class="h-10 w-full border border-gray-300 rounded-lg text-sm px-3 py-2 bg-transparent focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 shadow-theme-xs">
                        <option value="">Semua Status</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="h-10 bg-secondary-500 hover:bg-secondary-600 text-white px-4 rounded-lg text-sm font-medium inline-flex items-center gap-2 shadow-theme-xs transition-colors">
                        <i class="fa-solid fa-filter text-xs"></i> Filter
                    </button>
                    @if(request()->hasAny(['search', 'role_id', 'status']))
                    <a href="{{ route('users.index') }}" class="h-10 bg-gray-100 hover:bg-gray-200 text-gray-700 dark:bg-gray-700 dark:text-gray-200 px-4 rounded-lg text-sm font-medium inline-flex items-center gap-2 transition-colors">
                        <i class="fa-solid fa-xmark text-xs"></i> Reset
                    </a>
                    @endif
                </div>
            </form>
        </div>

        <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden"
             x-data="{ tableLoaded: false }"
             x-init="tableLoaded = true">

            <div x-show="!tableLoaded" class="p-6">
                <x-skeleton.table :rows="5" :cols="6" :showHeader="false" />
            </div>

            <div x-show="tableLoaded" class="overflow-x-auto transition-opacity duration-300">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-brand-500 text-white">
                    <tr>
                        <th class="px-6 py-3.5 text-left text-xs font-bold text-white uppercase tracking-wider">Nama</th>
                        <th class="px-6 py-3.5 text-left text-xs font-bold text-white uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3.5 text-left text-xs font-bold text-white uppercase tracking-wider">Role</th>
                        <th class="px-6 py-3.5 text-left text-xs font-bold text-white uppercase tracking-wider">Organisasi</th>
                        <th class="px-6 py-3.5 text-left text-xs font-bold text-white uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3.5 text-left text-xs font-bold text-white uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($users as $user)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $user->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->email }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->role->name ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->organization->name ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            @if($user->is_active)
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Aktif</span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Nonaktif</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <div class="flex items-center gap-2">
                                <button type="button" @click="$dispatch('open-modal', 'detail-user-{{ $user->id }}')" class="inline-flex items-center justify-center w-8 h-8 text-green-600 hover:text-green-900 hover:bg-green-50 rounded-lg transition-colors dark:text-green-400 dark:hover:bg-green-900/20" title="Lihat Detail">
                                    <i class="fa-solid fa-eye text-sm"></i>
                                </button>
                                <a href="{{ route('users.edit', $user) }}" class="inline-flex items-center justify-center w-8 h-8 text-blue-600 hover:text-blue-900 hover:bg-blue-50 rounded-lg transition-colors dark:text-blue-400 dark:hover:bg-blue-900/20" title="Edit Pengguna">
                                    <i class="fa-solid fa-pen-to-square text-sm"></i>
                                </a>
                                @if(auth()->id() === $user->id)
                                    <span class="inline-flex items-center justify-center w-8 h-8 text-gray-300 cursor-not-allowed" title="Tidak bisa menonaktifkan akun sendiri">
                                        <i class="fa-solid fa-trash text-sm"></i>
                                    </span>
                                @else
                                    <form action="{{ route('users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menonaktifkan pengguna {{ $user->name }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center justify-center w-8 h-8 text-red-600 hover:text-red-900 hover:bg-red-50 rounded-lg transition-colors dark:text-red-400 dark:hover:bg-red-900/20" title="Nonaktifkan Pengguna">
                                            <i class="fa-solid fa-trash text-sm"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">Tidak ada data pengguna</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $users->links() }}
        </div>

        @foreach($users as $user)
        <x-common.modal id="detail-user-{{ $user->id }}" title="Detail Pengguna" icon="fa-solid fa-user" maxWidth="lg">
            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400">Nama</label>
                        <p class="mt-1 text-sm font-medium text-gray-900 dark:text-white">{{ $user->name }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400">Username</label>
                        <p class="mt-1 text-sm font-medium text-gray-900 dark:text-white">{{ $user->username }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400">Email</label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-gray-200">{{ $user->email }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400">No. Telepon</label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-gray-200">{{ $user->phone ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400">Role</label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-gray-200">{{ $user->role->name ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400">Organisasi</label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-gray-200">{{ $user->organization->name ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400">Status</label>
                        @if($user->is_active)
                            <span class="mt-1 px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">Aktif</span>
                        @else
                            <span class="mt-1 px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">Nonaktif</span>
                        @endif
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400">Terakhir Login</label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-gray-200">{{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Belum pernah login' }}</p>
                    </div>
                </div>
            </div>

            <x-slot:footer>
                <a href="{{ route('users.edit', $user) }}" class="inline-flex items-center gap-2 bg-brand-500 hover:bg-brand-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                    <i class="fa-solid fa-pen-to-square text-sm"></i> Edit
                </a>
            </x-slot:footer>
        </x-common.modal>
        @endforeach
    </div>
</div>
@endsection
