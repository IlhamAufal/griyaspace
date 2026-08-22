@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="flex items-center gap-2 text-sm text-gray-500 mb-6">
            <a href="{{ route('dashboard') }}" class="hover:text-brand-500 transition-colors"><i class="fa-solid fa-house"></i></a>
            <i class="fa-solid fa-chevron-right text-xs"></i>
            <a href="{{ route('users.index') }}" class="hover:text-brand-500 transition-colors">Pengguna</a>
            <i class="fa-solid fa-chevron-right text-xs"></i>
            <span class="text-gray-900 font-medium">Edit</span>
        </nav>

        <h1 class="text-2xl font-bold text-gray-900 mb-6">Edit Pengguna</h1>

        <div class="bg-white shadow rounded-lg p-6">
            <form action="{{ route('users.update', $user) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-brand-500 focus:border-brand-500" required>
                        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-brand-500 focus:border-brand-500" required>
                        @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                        <input type="text" name="username" value="{{ old('username', $user->username) }}" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-brand-500 focus:border-brand-500" required>
                        @error('username') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Password (Kosongkan jika tidak diubah)</label>
                        <input type="password" name="password" id="password" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-brand-500 focus:border-brand-500">
                        <div id="password-requirements" class="mt-2 hidden">
                            <p id="req-length" class="text-xs flex items-center gap-1">
                                <i class="fa-solid fa-circle-check"></i> <span>Minimal 8 karakter</span>
                            </p>
                            <p id="req-uppercase" class="text-xs flex items-center gap-1">
                                <i class="fa-solid fa-circle-check"></i> <span>Minimal 1 huruf kapital (A-Z)</span>
                            </p>
                            <p id="req-match" class="text-xs flex items-center gap-1">
                                <i class="fa-solid fa-circle-check"></i> <span>Password cocok</span>
                            </p>
                        </div>
                        @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-brand-500 focus:border-brand-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                        <select name="role_id" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-brand-500 focus:border-brand-500" required>
                            <option value="">Pilih Role</option>
                            @foreach($roles as $role)
                            <option value="{{ $role->id }}" {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                            @endforeach
                        </select>
                        @error('role_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Organisasi</label>
                        <select name="organization_id" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-brand-500 focus:border-brand-500">
                            <option value="">Pilih Organisasi</option>
                            @foreach($organizations as $organization)
                            <option value="{{ $organization->id }}" {{ old('organization_id', $user->organization_id) == $organization->id ? 'selected' : '' }}>{{ $organization->name }}</option>
                            @endforeach
                        </select>
                        @error('organization_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">No. Telepon</label>
                        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-brand-500 focus:border-brand-500">
                        @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex items-center">
                        <label class="flex items-center mt-6">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }} class="rounded border-gray-300 text-brand-500 shadow-sm focus:ring-brand-500">
                            <span class="ml-2 text-sm text-gray-700">Status Akun Aktif</span>
                        </label>
                    </div>
                </div>

                <div class="mt-6 flex flex-wrap items-center justify-between gap-4 border-t pt-6">
                    <div>
                        @if($user->is_active && auth()->id() !== $user->id)
                            <button type="button" @click="$dispatch('open-modal', 'confirm-deactivate-user')" class="inline-flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                                <i class="fa-solid fa-user-slash text-sm"></i>
                                <span>Nonaktifkan Pengguna</span>
                            </button>
                        @endif
                    </div>
                    <div class="flex items-center gap-3">
                        <a href="{{ route('users.index') }}" class="inline-flex items-center gap-2 bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium transition-colors dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600">
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

            @if($user->is_active && auth()->id() !== $user->id)
                <form id="deactivate-user-form" action="{{ route('users.destroy', $user) }}" method="POST" class="hidden">
                    @csrf
                    @method('DELETE')
                </form>

                <!-- Modal Konfirmasi Nonaktifkan Pengguna -->
                <x-common.modal id="confirm-deactivate-user" title="Konfirmasi Nonaktifkan Pengguna" icon="fa-solid fa-triangle-exclamation" maxWidth="md">
                    <p class="text-gray-600 dark:text-gray-300">
                        Apakah Anda yakin ingin menonaktifkan akun <strong>{{ $user->name }}</strong> ({{ $user->email }})? Pengguna yang dinonaktifkan tidak akan dapat masuk ke sistem.
                    </p>

                    <x-slot:footer>
                        <button type="button" @click="close()" class="inline-flex items-center gap-2 bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium transition-colors dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600">
                            <i class="fa-solid fa-xmark text-sm"></i>
                            <span>Batal</span>
                        </button>
                        <button type="button" @click="document.getElementById('deactivate-user-form').submit()" class="inline-flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                            <i class="fa-solid fa-user-slash text-sm"></i>
                            <span>Ya, Nonaktifkan</span>
                        </button>
                    </x-slot:footer>
                </x-common.modal>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const passwordInput = document.getElementById('password');
    const confirmInput = document.getElementById('password_confirmation');
    const reqLength = document.getElementById('req-length');
    const reqUppercase = document.getElementById('req-uppercase');
    const reqMatch = document.getElementById('req-match');

    function validatePassword() {
        const val = passwordInput.value;
        const confirm = confirmInput.value;
        const hasLength = val.length >= 8;
        const hasUpper = /[A-Z]/.test(val);
        const isMatch = val.length > 0 && val === confirm;

        reqLength.className = 'text-xs flex items-center gap-1 ' + (hasLength ? 'text-green-600' : 'text-red-500');
        reqUppercase.className = 'text-xs flex items-center gap-1 ' + (hasUpper ? 'text-green-600' : 'text-red-500');
        reqMatch.className = 'text-xs flex items-center gap-1 ' + (isMatch ? 'text-green-600' : 'text-red-500');
    }

    passwordInput.addEventListener('input', function() {
        document.getElementById('password-requirements').classList.remove('hidden');
        validatePassword();
    });
    confirmInput.addEventListener('input', validatePassword);
</script>
@endpush
