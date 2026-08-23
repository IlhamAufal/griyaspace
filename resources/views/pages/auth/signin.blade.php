@extends('layouts.fullscreen-layout')

@section('content')
    <div class="relative z-1 bg-white p-6 sm:p-0 dark:bg-gray-900">
        <div class="relative flex h-screen w-full flex-col justify-center sm:p-0 lg:flex-row dark:bg-gray-900">
            <!-- Form -->
            <div class="flex w-full flex-1 flex-col lg:w-1/2">
                <div class="mx-auto flex w-full max-w-md flex-1 flex-col justify-center">
                    <div>
                        <div class="mb-5 sm:mb-8">
                            <h1 class="text-title-sm sm:text-title-md mb-2 font-semibold text-gray-800 dark:text-white/90">
                                Masuk
                            </h1>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                Masukkan username dan password Anda untuk masuk.
                            </p>
                        </div>
                        <div>
                            @if ($errors->any())
                                <div class="mb-4 rounded-lg bg-red-50 p-4 text-sm text-red-600 dark:bg-red-900/20 dark:text-red-400">
                                    <ul class="list-disc list-inside">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <form method="POST" action="{{ route('login') }}">
                                @csrf
                                <div class="space-y-5">
                                    <!-- Username -->
                                    <div>
                                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                            Username<span class="text-error-500">*</span>
                                        </label>
                                        <input type="text" id="username" name="username" value="{{ old('username') }}"
                                            placeholder="Masukkan username Anda"
                                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                                    </div>
                                    <!-- Password -->
                                    <div>
                                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                            Password<span class="text-error-500">*</span>
                                        </label>
                                        <div x-data="{ showPassword: false }" class="relative">
                                            <input :type="showPassword ? 'text' : 'password'" name="password"
                                                placeholder="Masukkan password Anda"
                                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent py-2.5 pr-11 pl-4 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                                            <span @click="showPassword = !showPassword"
                                                 class="absolute top-1/2 right-4 z-30 -translate-y-1/2 cursor-pointer text-gray-500 dark:text-gray-400">
                                                 <i x-show="!showPassword" class="fa-solid fa-eye text-base"></i>
                                                 <i x-show="showPassword" class="fa-solid fa-eye-slash text-base"></i>
                                             </span>
                                         </div>
                                     </div>
                                     <!-- Button -->
                                     <div>
                                         <button
                                             class="bg-brand-500 shadow-theme-xs hover:bg-brand-600 flex w-full items-center justify-center rounded-lg px-4 py-3 text-sm font-medium text-white transition">
                                             Masuk
                                         </button>
                                     </div>
                                 </div>
                             </form>
                         </div>
                     </div>
                 </div>
             </div>

             <div class="bg-brand-950 relative hidden h-full w-full items-center lg:grid lg:w-1/2 dark:bg-white/5">
                 <div class="z-1 flex items-center justify-center">
                     <x-common.common-grid-shape/>
                     <div class="flex max-w-xs flex-col items-center">
                         <p class="text-center text-gray-400 dark:text-white/60">
                             GriyaSpace - Sistem Peminjaman Ruangan
                         </p>
                     </div>
                 </div>
             </div>
        </div>
    </div>
@endsection
