@props([
    'title' => '',
    'subtitle' => 'Pengajuan',
    'value' => 0,
    'color' => 'navy', // navy, kuning, toska
])

@php
    $colorConfig = [
        'navy' => [
            'bg' => 'bg-[#2F3185]',
            'text' => 'text-[#2F3185]',
        ],
        'kuning' => [
            // Gunakan warna teks yang sedikit lebih tegas agar terbaca jelas
            'bg' => 'bg-[#FFB800]',
            'text' => 'text-[#D97706]', 
        ],
        'toska' => [
            'bg' => 'bg-[#1CBDB3]',
            'text' => 'text-[#0D9488]',
        ],
    ];

    $selectedColor = $colorConfig[$color] ?? $colorConfig['navy'];
@endphp

<div class="relative overflow-hidden rounded-2xl sm:rounded-3xl {{ $selectedColor['bg'] }} p-4 sm:p-5 shadow-sm flex items-center justify-between min-h-[96px] text-white">
    <!-- Sisi Teks Kiri -->
    <div class="flex flex-col justify-center pr-2 z-10 min-w-0">
        @if($subtitle)
            <span class="text-xs sm:text-sm font-medium tracking-wide text-white/90 truncate">
                {{ $subtitle }}
            </span>
        @endif
        <h3 class="text-lg sm:text-xl lg:text-2xl font-bold tracking-tight leading-tight truncate mt-0.5">
            {{ $title }}
        </h3>
    </div>

    <!-- Sisi Badge Angka Kanan (Melengkung U-shape menempel ke kanan) -->
    <div class="absolute right-0 top-1/2 -translate-y-1/2 h-20 w-20 sm:h-24 sm:w-24 bg-[#ffffff] rounded-l-[45px] sm:rounded-l-[50px] flex items-center justify-center pl-2">
        <span class="text-3xl sm:text-4xl font-extrabold {{ $selectedColor['text'] }} select-none">
            {{ $value }}
        </span>
    </div>
</div>