@extends('layouts.fullscreen-layout')
@php
    $title = 'Masuk';
@endphp

@section('content')
    <div class="relative z-1 min-h-screen bg-white dark:bg-gray-900">
        <!-- Theme Toggle ( pojok kanan atas ) -->
        <div class="absolute top-4 right-4 z-50">
            <button
                type="button"
                class="relative inline-flex h-9 w-16 items-center justify-between rounded-full bg-white/90 p-1 border border-gray-200 shadow-md backdrop-blur-xs transition-colors dark:bg-gray-800/90 dark:border-gray-700 focus:outline-none cursor-pointer"
                @click="$store.theme.toggle()"
                :aria-label="$store.theme.theme === 'dark' ? 'Ganti ke mode terang' : 'Ganti ke mode gelap'"
                :title="$store.theme.theme === 'dark' ? 'Mode Gelap (Klik untuk Terang)' : 'Mode Terang (Klik untuk Gelap)'">
                
                <!-- Sliding Thumb -->
                <span
                    class="absolute top-1 left-1 flex h-7 w-7 items-center justify-center rounded-full bg-white shadow-theme-xs transition-transform duration-300 ease-in-out dark:bg-gray-900 border border-gray-100 dark:border-gray-800"
                    :class="$store.theme.theme === 'dark' ? 'translate-x-7' : 'translate-x-0'">
                </span>

                <!-- Sun Icon (Light Mode) -->
                <span class="z-10 flex h-7 w-7 items-center justify-center text-xs transition-colors duration-200"
                    :class="$store.theme.theme === 'light' ? 'text-amber-500 font-semibold' : 'text-gray-400 dark:text-gray-500'">
                    <i class="fa-solid fa-sun text-sm"></i>
                </span>

                <!-- Moon Icon (Dark Mode) -->
                <span class="z-10 flex h-7 w-7 items-center justify-center text-xs transition-colors duration-200"
                    :class="$store.theme.theme === 'dark' ? 'text-blue-400 font-semibold' : 'text-gray-400 dark:text-gray-500'">
                    <i class="fa-solid fa-moon text-sm"></i>
                </span>
            </button>
        </div>

        <div class="relative flex min-h-screen w-full flex-col lg:flex-row">

            <!-- Sisi Kiri (Form Login & Logo di Pojok Kiri Atas) -->
            <div class="relative flex w-full flex-1 flex-col justify-between p-3 sm:p-5 lg:w-1/2 lg:p-6">
                <!-- Pojok Kiri Atas: 2 Logo Kecil Berdampingan (Ukuran Sama Client-Side) -->
                <div class="flex items-center gap-3">
                    <img src="{{ asset('storage/logo_text.png') }}" alt="Logo Griya Mahasiswa"
                        class="h-8 sm:h-9 w-auto object-contain" />
                    <span class="h-5 w-px bg-gray-300 dark:bg-gray-700"></span>
                    <img src="{{ asset('storage/dkpti-dark.png') }}" alt="Logo DKPTI"
                        class="h-8 sm:h-9 w-auto object-contain" />
                </div>

                <!-- Form Container di Tengah -->
                <div class="mx-auto my-auto w-full max-w-md py-8">
                    <div class="mb-6 sm:mb-8">
                        <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-gray-900 dark:text-white">
                            Masuk
                        </h1>
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                            Masukkan email dan password Anda untuk masuk.
                        </p>
                    </div>

                    @if ($errors->any())
                        <div
                            class="mb-5 rounded-xl bg-red-50 p-4 text-sm text-red-600 dark:bg-red-900/20 dark:text-red-400 border border-red-200 dark:border-red-800">
                            <ul class="list-disc list-inside space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="space-y-5">
                            <!-- Email -->
                            <div>
                                <label for="email"
                                    class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Email <span class="text-red-500">*</span>
                                </label>
                                <input type="email" id="email" name="email" value="{{ old('email') }}" required
                                    autofocus placeholder="Masukkan email Anda"
                                    class="h-11 w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder:text-gray-400 focus:border-brand-500 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder:text-gray-500 shadow-xs transition" />
                            </div>

                            <!-- Password -->
                            <div>
                                <label for="password"
                                    class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Password <span class="text-red-500">*</span>
                                </label>
                                <div x-data="{ showPassword: false }" class="relative">
                                    <input :type="showPassword ? 'text' : 'password'" id="password" name="password"
                                        required placeholder="Masukkan password Anda"
                                        class="h-11 w-full rounded-lg border border-gray-300 bg-white py-2.5 pr-11 pl-4 text-sm text-gray-900 placeholder:text-gray-400 focus:border-brand-500 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder:text-gray-500 shadow-xs transition" />
                                    <button type="button" @click="showPassword = !showPassword"
                                        class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 cursor-pointer focus:outline-none">
                                        <i x-show="!showPassword" class="fa-solid fa-eye text-sm"></i>
                                        <i x-show="showPassword" class="fa-solid fa-eye-slash text-sm"
                                            style="display: none;"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="pt-1">
                                <button type="submit"
                                    class="flex w-full items-center justify-center rounded-lg bg-brand-500 hover:bg-brand-600 px-4 py-3 text-sm font-semibold text-white shadow-xs transition duration-150 cursor-pointer">
                                    Masuk
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Footer note -->
                <div class="text-xs text-gray-400 dark:text-gray-500">
                    &copy; {{ date('Y') }} Griya Mahasiswa. All rights reserved.
                </div>
            </div>

            <!-- Sisi Kanan: Foto gm.webp dengan Layer Gradient & Teks -->
            <div class="relative hidden min-h-screen w-full lg:block lg:w-1/2 overflow-hidden select-none">
                <!-- Background Image -->
                <img src="{{ asset('storage/gm.webp') }}" alt="Griya Mahasiswa"
                    class="absolute inset-0 h-full w-full object-cover object-center" />

                <!-- Layer Gradient Hitam (bawah gelap, semakin ke atas semakin tipis) -->
                <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent"></div>

                <!-- Text di Depan Layer Gradient -->
                <div class="absolute inset-x-0 bottom-0 z-10 p-8 sm:p-12">
                    <h2 class="text-xl sm:text-2xl lg:text-3xl font-bold text-white tracking-wide drop-shadow-md">
                        Griya Mahasiswa - Pengajuan Tempat
                    </h2>
                </div>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
    @if (request('expired'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                if (window.toast) {
                    window.toast.warning('Session telah expired. Silakan login kembali.', 'Session Berakhir');
                }
            });
        </script>
    @endif
@endpush
