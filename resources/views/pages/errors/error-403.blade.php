@extends('layouts.fullscreen-right-layout')

@php
    $title = '403 - Akses Ditolak';
    $currentYear = date('Y');
@endphp

@section('content')
<div class="text-center">
    <h1 class="mb-2 font-bold text-gray-800 text-title-md dark:text-white/90 xl:text-title-2xl">
        403
    </h1>

    <div class="mx-auto my-6 max-w-[280px] sm:max-w-[340px]">
        <img src="/images/error/404.svg" alt="403" class="dark:hidden w-full h-auto" />
        <img src="/images/error/404-dark.svg" alt="403" class="hidden dark:block w-full h-auto" />
    </div>

    <h2 class="mb-2 text-xl font-semibold text-gray-800 dark:text-white sm:text-2xl">
        Akses Ditolak
    </h2>

    <p class="mb-8 text-sm text-gray-500 dark:text-gray-400">
        Anda tidak memiliki hak akses untuk membuka halaman ini.
    </p>

    <a href="{{ url('/') }}"
        class="inline-flex items-center justify-center gap-2 rounded-lg bg-brand-500 px-6 py-3 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600 transition-colors">
        <i class="fa-solid fa-house text-xs"></i>
        Kembali ke Beranda
    </a>

    <p class="mt-8 text-xs text-gray-400 dark:text-gray-500">
        &copy; {{ $currentYear }} GriyaSpace
    </p>
</div>
@endsection
