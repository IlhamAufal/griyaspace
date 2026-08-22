@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="flex items-center gap-2 text-sm text-gray-500 mb-6">
            <a href="{{ route('dashboard') }}" class="hover:text-blue-600 transition-colors"><i class="fa-solid fa-house"></i></a>
            <i class="fa-solid fa-chevron-right text-xs"></i>
            <a href="{{ route('bookings.index') }}" class="hover:text-blue-600 transition-colors">Pengajuan</a>
            <i class="fa-solid fa-chevron-right text-xs"></i>
            <span class="text-gray-900 font-medium">{{ $booking->booking_number }}</span>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-2xl border border-gray-200 dark:border-gray-700/60 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h1 class="text-xl font-bold text-gray-900 dark:text-white">Detail Pengajuan</h1>
                        @php
                            $statusColors = [
                                'submitted' => 'bg-yellow-100 text-yellow-800',
                                'approved' => 'bg-green-100 text-green-800',
                                'rejected' => 'bg-red-100 text-red-800',
                                'revision' => 'bg-orange-100 text-orange-800',
                                'cancelled' => 'bg-gray-100 text-gray-800',
                            ];
                            $statusLabels = [
                                'submitted' => 'Diajukan',
                                'approved' => 'Disetujui',
                                'rejected' => 'Ditolak',
                                'revision' => 'Revisi',
                                'cancelled' => 'Dibatalkan',
                            ];
                        @endphp
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColors[$booking->status] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ $statusLabels[$booking->status] ?? $booking->status }}
                        </span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">No. Booking</label>
                            <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $booking->booking_number }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Organisasi</label>
                            <p class="text-sm text-gray-900 dark:text-white">{{ $booking->organization->name ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Ruangan</label>
                            <p class="text-sm text-gray-900 dark:text-white">{{ $booking->room->name }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Tanggal</label>
                            <p class="text-sm text-gray-900 dark:text-white">{{ $booking->booking_date->format('d/m/Y') }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Waktu</label>
                            <p class="text-sm text-gray-900 dark:text-white">{{ $booking->start_time }} - {{ $booking->end_time }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Kegiatan</label>
                            <p class="text-sm text-gray-900 dark:text-white">{{ $booking->activity_name }}</p>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Tujuan</label>
                            <p class="text-sm text-gray-900 dark:text-white">{{ $booking->purpose }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Jumlah Peserta</label>
                            <p class="text-sm text-gray-900 dark:text-white">{{ $booking->participant_count }} orang</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Penanggung Jawab</label>
                            <p class="text-sm text-gray-900 dark:text-white">{{ $booking->person_in_charge }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">No. Telepon</label>
                            <p class="text-sm text-gray-900 dark:text-white">{{ $booking->contact_phone }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Pengaju</label>
                            <p class="text-sm text-gray-900 dark:text-white">{{ $booking->submittedBy->name ?? '-' }}</p>
                        </div>
                    </div>

                    @if($booking->admin_note)
                    <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700/60">
                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Catatan Admin</label>
                        <p class="text-sm text-gray-900 dark:text-white">{{ $booking->admin_note }}</p>
                    </div>
                    @endif
                </div>

                @if(in_array($booking->status, ['submitted', 'revision']) && auth()->user()->isAdmin())
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-2xl border border-gray-200 dark:border-gray-700/60 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Aksi Pengajuan</h2>
                    <div class="flex flex-wrap gap-3">
                        <button type="button" onclick="openDecisionModal('approve')" class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-5 py-2.5 rounded-lg text-sm font-medium transition-colors">
                            <i class="fa-solid fa-check text-sm"></i> Setujui
                        </button>
                        <button type="button" onclick="openDecisionModal('revision')" class="inline-flex items-center gap-2 bg-orange-500 hover:bg-orange-600 text-white px-5 py-2.5 rounded-lg text-sm font-medium transition-colors">
                            <i class="fa-solid fa-rotate-left text-sm"></i> Minta Revisi
                        </button>
                        <button type="button" onclick="openDecisionModal('reject')" class="inline-flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white px-5 py-2.5 rounded-lg text-sm font-medium transition-colors">
                            <i class="fa-solid fa-xmark text-sm"></i> Tolak
                        </button>
                    </div>
                </div>
                @endif

                @if(in_array($booking->status, ['submitted', 'revision']))
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-2xl border border-gray-200 dark:border-gray-700/60 p-6">
                    <form action="{{ route('bookings.cancel', $booking) }}" method="POST" onsubmit="return confirm('Yakin ingin membatalkan pengajuan ini?')">
                        @csrf
                        <button type="submit" class="inline-flex items-center gap-2 bg-gray-500 hover:bg-gray-600 text-white px-5 py-2.5 rounded-lg text-sm font-medium transition-colors">
                            <i class="fa-solid fa-ban text-sm"></i> Batalkan Pengajuan
                        </button>
                    </form>
                </div>
                @endif

                <div class="flex justify-start">
                    <a href="{{ route('bookings.index') }}" class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 px-5 py-2.5 rounded-lg text-sm font-medium transition-colors dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600">
                        <i class="fa-solid fa-arrow-left text-sm"></i> Kembali
                    </a>
                </div>
            </div>

            <div class="lg:col-span-1">
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-2xl border border-gray-200 dark:border-gray-700/60 p-6 sticky top-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-5">Riwayat Status</h2>

                    @php
                        $timelineConfig = [
                            'submitted' => ['color' => 'bg-yellow-500', 'ring' => 'ring-yellow-100', 'icon' => 'fa-solid fa-paper-plane', 'label' => 'Diajukan'],
                            'revision' => ['color' => 'bg-orange-500', 'ring' => 'ring-orange-100', 'icon' => 'fa-solid fa-rotate-left', 'label' => 'Revisi'],
                            'approved' => ['color' => 'bg-green-500', 'ring' => 'ring-green-100', 'icon' => 'fa-solid fa-check', 'label' => 'Disetujui'],
                            'rejected' => ['color' => 'bg-red-500', 'ring' => 'ring-red-100', 'icon' => 'fa-solid fa-xmark', 'label' => 'Ditolak'],
                            'cancelled' => ['color' => 'bg-gray-400', 'ring' => 'ring-gray-100', 'icon' => 'fa-solid fa-ban', 'label' => 'Dibatalkan'],
                        ];
                    @endphp

                    @if($booking->history->count())
                    <div class="flow-root">
                        <ul class="-mb-8">
                            @foreach($booking->history->sortByDesc('created_at') as $item)
                            @php
                                $config = $timelineConfig[$item->new_status] ?? ['color' => 'bg-gray-400', 'ring' => 'ring-gray-100', 'icon' => 'fa-solid fa-circle-dot', 'label' => $item->new_status];
                            @endphp
                            <li>
                                <div class="relative pb-8">
                                    @if(!$loop->last)
                                    <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200 dark:bg-gray-700"></span>
                                    @endif
                                    <div class="relative flex items-start space-x-3">
                                        <div class="flex-shrink-0">
                                            <span class="h-8 w-8 rounded-full {{ $config['color'] }} flex items-center justify-center ring-4 {{ $config['ring'] }} text-white">
                                                <i class="{{ $config['icon'] }} text-xs"></i>
                                            </span>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $config['label'] }}</div>
                                            @if($item->note)
                                            <p class="mt-0.5 text-xs text-gray-500 dark:text-gray-400">{{ $item->note }}</p>
                                            @endif
                                            <p class="mt-0.5 text-xs text-gray-400 dark:text-gray-500">
                                                {{ $item->changedBy->name ?? '-' }} &middot; {{ $item->created_at->format('d/m/Y H:i') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @else
                    <p class="text-sm text-gray-500 dark:text-gray-400 text-center py-4">Belum ada riwayat status</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<div id="decisionModal" class="fixed inset-0 z-[99999] hidden bg-black/50" style="display:none;">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl w-full max-w-lg overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Keputusan Pengajuan</h3>
                <button onclick="closeDecisionModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>
            <form id="decisionForm" method="POST" class="p-6">
                @csrf
                <input type="hidden" name="action" id="decisionAction" value="">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Catatan <span class="text-red-500">*</span></label>
                    <textarea name="admin_note" rows="3" placeholder="Masukkan catatan keputusan..." class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-3 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 shadow-theme-xs" required></textarea>
                </div>
                <div class="flex items-center justify-end gap-3">
                    <button type="button" onclick="closeDecisionModal()" class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 px-5 py-2.5 rounded-lg text-sm font-medium transition-colors dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600">
                        Batal
                    </button>
                    <button type="submit" id="decisionSubmitBtn" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg text-sm font-medium shadow-theme-xs transition-colors text-white bg-gray-400 cursor-not-allowed" disabled>
                        <span id="decisionSubmitText">Pilih Keputusan</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openDecisionModal(action) {
    var modal = document.getElementById('decisionModal');
    var form = document.getElementById('decisionForm');
    var btn = document.getElementById('decisionSubmitBtn');
    var text = document.getElementById('decisionSubmitText');
    form.action = '{{ route("bookings.decision", $booking) }}';
    document.getElementById('decisionAction').value = action;
    form.querySelector('[name="admin_note"]').value = '';
    btn.disabled = false;
    if (action === 'approve') {
        btn.className = 'inline-flex items-center gap-2 px-5 py-2.5 rounded-lg text-sm font-medium shadow-theme-xs transition-colors text-white bg-green-600 hover:bg-green-700';
        text.textContent = 'Setujui';
    } else if (action === 'reject') {
        btn.className = 'inline-flex items-center gap-2 px-5 py-2.5 rounded-lg text-sm font-medium shadow-theme-xs transition-colors text-white bg-red-600 hover:bg-red-700';
        text.textContent = 'Tolak';
    } else if (action === 'revision') {
        btn.className = 'inline-flex items-center gap-2 px-5 py-2.5 rounded-lg text-sm font-medium shadow-theme-xs transition-colors text-white bg-orange-500 hover:bg-orange-600';
        text.textContent = 'Minta Revisi';
    }
    modal.style.display = 'block';
}

function closeDecisionModal() {
    var modal = document.getElementById('decisionModal');
    modal.style.display = 'none';
}
</script>
@endsection
