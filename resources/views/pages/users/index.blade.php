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

        <div x-data="{
            search: '{{ request('search') }}',
            filters: { role_id: '{{ request('role_id') }}', status: '{{ request('status') }}' },
            sort: '{{ request('sort') }}',
            direction: '{{ request('direction', 'asc') }}',
            loading: false,
            timer: null,
            doSearch() {
                clearTimeout(this.timer);
                this.timer = setTimeout(() => { this.page(1); }, 400);
            },
            applyFilter() { this.page(1); },
            clearFilters() {
                this.search = '';
                this.filters = { role_id: '', status: '' };
                this.page(1);
            },
            sortColumn(col, dir) {
                this.sort = col;
                this.direction = dir;
                this.page(1);
            },
            page(p) {
                this.loading = true;
                const params = new URLSearchParams();
                if (this.search) params.set('search', this.search);
                if (this.filters.role_id) params.set('role_id', this.filters.role_id);
                if (this.filters.status) params.set('status', this.filters.status);
                if (this.sort) params.set('sort', this.sort);
                if (this.direction) params.set('direction', this.direction);
                params.set('page', p);
                const url = '{{ route('users.index') }}?' + params.toString();
                fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                    .then(r => r.text())
                    .then(html => {
                        const doc = new DOMParser().parseFromString(html, 'text/html');
                        const newTable = doc.querySelector('#table-area');
                        if (newTable) document.querySelector('#table-area').innerHTML = newTable.innerHTML;
                        history.replaceState(null, '', url);
                        this.loading = false;
                        this.$nextTick(() => {
                            document.querySelectorAll('#table-area a.pagination-link').forEach(link => {
                                link.addEventListener('click', e => {
                                    e.preventDefault();
                                    const page = new URL(link.href).searchParams.get('page') || 1;
                                    this.page(page);
                                });
                            });
                        });
                    })
                    .catch(() => { this.loading = false; });
            }
        }" x-init="$nextTick(() => {
            document.querySelectorAll('#table-area a.pagination-link').forEach(link => {
                link.addEventListener('click', e => {
                    e.preventDefault();
                    const page = new URL(link.href).searchParams.get('page') || 1;
                    this.page(page);
                });
            });
        })" @sort.window="sortColumn($event.detail.column, $event.detail.direction)">

            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-200 dark:border-gray-700/60 p-4 mb-5">
                <div class="flex flex-wrap items-end gap-4">
                    <div class="flex-1 min-w-[220px]">
                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">Pencarian</label>
                        <div class="relative">
                            <input type="text" x-model="search" @input.debounce.400ms="doSearch()" placeholder="Nama atau email..." class="h-10 w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm bg-transparent placeholder:text-gray-500 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 shadow-theme-xs">
                            <i x-show="!loading" class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-500 dark:text-gray-400 text-xs"></i>
                            <i x-show="loading" x-cloak class="fa-solid fa-spinner fa-spin absolute left-3.5 top-1/2 -translate-y-1/2 text-brand-500 text-xs"></i>
                        </div>
                    </div>
                    <div class="w-48">
                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">Role</label>
                        <select x-model="filters.role_id" @change="applyFilter()" class="h-10 w-full border border-gray-300 rounded-lg text-sm px-3 py-2 bg-transparent focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 shadow-theme-xs">
                            <option value="">Semua Role</option>
                            @foreach($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="w-44">
                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">Status</label>
                        <select x-model="filters.status" @change="applyFilter()" class="h-10 w-full border border-gray-300 rounded-lg text-sm px-3 py-2 bg-transparent focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 shadow-theme-xs">
                            <option value="">Semua Status</option>
                            <option value="active">Aktif</option>
                            <option value="inactive">Nonaktif</option>
                        </select>
                    </div>
                    <button type="button" @click="clearFilters()" x-show="search || filters.role_id || filters.status" x-cloak class="h-10 bg-gray-100 hover:bg-gray-200 text-gray-700 dark:bg-gray-700 dark:text-gray-200 px-4 rounded-lg text-sm font-medium inline-flex items-center gap-2 transition-colors">
                        <i class="fa-solid fa-xmark text-xs"></i> Reset
                    </button>
                </div>
            </div>

            <div id="table-area">
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
                    <div class="overflow-x-auto transition-opacity duration-300" :class="loading ? 'opacity-50 pointer-events-none' : ''">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-brand-500 text-white">
                            <tr>
                                <x-table.sortable-header column="name" label="Nama" />
                                <x-table.sortable-header column="email" label="Email" />
                                <th class="px-6 py-3.5 text-left text-xs font-bold text-white uppercase tracking-wider">Role</th>
                                <th class="px-6 py-3.5 text-left text-xs font-bold text-white uppercase tracking-wider">Organisasi</th>
                                <x-table.sortable-header column="is_active" label="Status" />
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
                                    <div class="flex items-center gap-3">
                                        <button type="button" @click="$dispatch('open-modal', 'detail-user-{{ $user->id }}')" class="relative inline-flex items-center justify-center w-8 h-8 after:absolute after:-inset-1.5 after:content-[''] text-green-600 hover:text-green-900 hover:bg-green-50 rounded-lg transition-colors dark:text-green-400 dark:hover:bg-green-900/20" title="Lihat Detail">
                                            <i class="fa-solid fa-eye text-sm"></i>
                                        </button>
                                        @if(auth()->user()->email === 'bagmawa@ums.ac.id')
                                        <a href="{{ route('users.edit', $user) }}" class="relative inline-flex items-center justify-center w-8 h-8 after:absolute after:-inset-1.5 after:content-[''] text-blue-600 hover:text-blue-900 hover:bg-blue-50 rounded-lg transition-colors dark:text-blue-400 dark:hover:bg-blue-900/20" title="Edit Pengguna">
                                            <i class="fa-solid fa-pen-to-square text-sm"></i>
                                        </a>
                                        @endif
                                        @if(auth()->id() === $user->id)
                                            <span class="inline-flex items-center justify-center w-8 h-8 text-gray-300 cursor-not-allowed" title="Tidak bisa menonaktifkan akun sendiri">
                                                <i class="fa-solid fa-trash text-sm"></i>
                                            </span>
                                        @else
                                            <form action="{{ route('users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menonaktifkan pengguna {{ $user->name }}?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="relative inline-flex items-center justify-center w-8 h-8 after:absolute after:-inset-1.5 after:content-[''] text-red-600 hover:text-red-900 hover:bg-red-50 rounded-lg transition-colors dark:text-red-400 dark:hover:bg-red-900/20" title="Nonaktifkan Pengguna">
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
                </div>

                <div class="mt-4">
                    {{ $users->links() }}
                </div>
            </div>
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
                @if(auth()->user()->email === 'bagmawa@ums.ac.id')
                <a href="{{ route('users.edit', $user) }}" class="inline-flex items-center gap-2 bg-brand-500 hover:bg-brand-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                    <i class="fa-solid fa-pen-to-square text-sm"></i> Edit
                </a>
                @endif
            </x-slot:footer>
        </x-common.modal>
        @endforeach
    </div>
</div>
@endsection
