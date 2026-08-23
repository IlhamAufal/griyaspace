@extends('layouts.fullscreen-right-layout')

@php
    $title = '503 - Sedang Pemeliharaan';
    $currentYear = date('Y');
@endphp

@section('content')
<div class="text-center">
    <h1 class="mb-2 font-bold text-gray-800 text-title-md dark:text-white/90 xl:text-title-2xl">
        503
    </h1>

    <div class="mx-auto my-6 max-w-[280px] sm:max-w-[340px]">
        <img src="/images/error/503.svg" alt="503" class="dark:hidden w-full h-auto" />
        <img src="/images/error/503-dark.svg" alt="503" class="hidden dark:block w-full h-auto" />
    </div>

    <h2 class="mb-2 text-xl font-semibold text-gray-800 dark:text-white sm:text-2xl">
        Layanan Sedang Pemeliharaan
    </h2>

    <p class="mb-8 text-sm text-gray-500 dark:text-gray-400">
        Sistem sedang dalam peningkatan atau pemeliharaan rutin. Silakan coba kembali beberapa saat lagi.
    </p>

    <a href="{{ url('/') }}"
        class="inline-flex items-center justify-center gap-2 rounded-lg bg-brand-500 px-6 py-3 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600 transition-colors">
        <i class="fa-solid fa-rotate-right text-xs"></i>
        Muat Ulang
    </a>

    <p class="mt-8 text-xs text-gray-400 dark:text-gray-500">
        &copy; {{ $currentYear }} GriyaSpace
    </p>
</div>
@endsection
