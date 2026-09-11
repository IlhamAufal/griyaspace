{{-- Step 2: Kalender Bulan & Pemilihan Tanggal --}}
<div x-show="step === 2" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" style="display:none;">
    <div class="mb-6 pb-4 border-b border-gray-100 dark:border-gray-700/60 flex flex-wrap items-center justify-between gap-4">
        <div>
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                <span class="w-7 h-7 rounded-full bg-[#FFB800] text-white flex items-center justify-center text-xs font-bold">2</span>
                <span>Pilih Tanggal & Waktu Peminjaman</span>
            </h2>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 ml-9">
                Tarik (drag) pada kalender untuk memilih maksimal 3 hari sekaligus. Semua tanggal share jam yang sama.
            </p>
        </div>
    </div>

    {{-- Race Condition / Conflict Error Banner --}}
    @if(session('conflict_step') || $errors->has('schedule_conflict') || $errors->has('start_time'))
    <div class="mb-6 p-4 rounded-xl bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300 flex items-start gap-3">
        <div class="w-8 h-8 rounded-full bg-red-100 dark:bg-red-800/50 flex items-center justify-center shrink-0 text-red-600 dark:text-red-200 mt-0.5">
            <i class="fa-solid fa-triangle-exclamation text-base"></i>
        </div>
        <div class="text-sm">
            <h4 class="font-bold text-red-800 dark:text-red-200">Terjadi Bentrok Jadwal!</h4>
            <p class="mt-0.5 text-xs text-red-600 dark:text-red-300">
                {{ $errors->first('schedule_conflict') ?: $errors->first('start_time') ?: 'Slot jadwal yang Anda pilih baru saja dipesan oleh pengguna lain.' }}
                Kalender telah diperbarui. Silakan pilih slot waktu kosong lainnya.
            </p>
        </div>
    </div>
    @endif

    {{-- Calendar Controls Bar --}}
    <div class="bg-gray-50 dark:bg-gray-900/50 rounded-xl p-4 border border-gray-200 dark:border-gray-700/60 mb-4">
        <div class="flex flex-wrap items-center justify-between gap-3">
            {{-- Navigation Buttons --}}
            <div class="flex items-center gap-2">
                <button type="button" @click="calendarPrev()"
                    class="p-2 rounded-lg bg-white dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200 border border-gray-200 dark:border-gray-700 text-xs font-medium transition-colors shadow-xs">
                    <i class="fa-solid fa-chevron-left mr-1"></i> Sebelumnya
                </button>
                <button type="button" @click="calendarToday()"
                    class="px-3 py-2 rounded-lg bg-white dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200 border border-gray-200 dark:border-gray-700 text-xs font-medium transition-colors shadow-xs">
                    Hari Ini
                </button>
                <button type="button" @click="calendarNext()"
                    class="p-2 rounded-lg bg-white dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200 border border-gray-200 dark:border-gray-700 text-xs font-medium transition-colors shadow-xs">
                    Selanjutnya <i class="fa-solid fa-chevron-right ml-1"></i>
                </button>
            </div>

            {{-- Month/Year Display & Refresh --}}
            <div class="flex items-center gap-2">
                <span class="text-sm font-semibold text-gray-800 dark:text-gray-200" x-text="calendarTitle"></span>
                <button type="button" @click="refreshCalendar()" title="Refresh Kalender"
                    class="p-2 rounded-lg bg-white dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200 border border-gray-200 dark:border-gray-700 text-xs transition-colors shadow-xs">
                    <i class="fa-solid fa-rotate text-xs"></i>
                </button>
            </div>
        </div>

        {{-- Guide & Legend --}}
        <div class="mt-3 pt-3 border-t border-gray-200/70 dark:border-gray-700/60 flex flex-wrap items-center justify-between gap-2 text-xs">
            <div class="flex items-center gap-2 text-gray-600 dark:text-gray-400">
                <i class="fa-solid fa-lightbulb text-amber-500"></i>
                <span><b>Cara Memilih:</b> Klik & geser (drag) tanggal di kalender. Maks 3 hari.</span>
            </div>
            <div class="flex items-center gap-4 text-[11px]">
                <span class="inline-flex items-center gap-1.5 text-gray-600 dark:text-gray-400">
                    <span class="w-3 h-3 rounded-xs bg-emerald-100 dark:bg-emerald-900/50 border border-emerald-400"></span> Kosong
                </span>
                <span class="inline-flex items-center gap-1.5 text-gray-600 dark:text-gray-400">
                    <span class="w-3 h-3 rounded-xs bg-red-400"></span> Terisi
                </span>
                <span class="inline-flex items-center gap-1.5 text-gray-600 dark:text-gray-400">
                    <span class="w-3 h-3 rounded-xs bg-brand-500"></span> Pilihan Anda
                </span>
            </div>
        </div>
    </div>

    {{-- Interactive Calendar Container --}}
    <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden shadow-xs">
        <div id="step2-calendar" class="fc fc-media-screen fc-direction-ltr fc-theme-standard p-2 text-xs min-h-[420px]"></div>
    </div>

    {{-- Selected Dates & Time Form --}}
    <div class="mt-5 p-5 bg-brand-50/40 dark:bg-brand-500/5 rounded-xl border border-brand-200/80 dark:border-brand-500/20">
        <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-3 flex items-center gap-2">
            <i class="fa-solid fa-calendar-check text-brand-500"></i>
            <span>Tanggal & Waktu Peminjaman</span>
            <span class="text-xs font-normal text-gray-500 dark:text-gray-400" x-show="selectedDates.length > 0">
                (<span x-text="selectedDates.length"></span> hari terpilih)
            </span>
        </h3>

        {{-- Selected Dates List --}}
        <div x-show="selectedDates.length > 0" class="mb-4">
            <div class="flex flex-wrap gap-2">
                <template x-for="(date, index) in selectedDates" :key="date">
                    <div class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white dark:bg-gray-800 rounded-lg border border-brand-200 dark:border-brand-700 text-xs">
                        <i class="fa-solid fa-calendar-day text-brand-500"></i>
                        <span class="font-medium text-gray-800 dark:text-gray-200" x-text="formatDateShort(date)"></span>
                        <button type="button" @click="removeDate(index)"
                            class="ml-1 text-gray-400 hover:text-red-500 transition-colors">
                            <i class="fa-solid fa-xmark text-[10px]"></i>
                        </button>
                    </div>
                </template>
            </div>
        </div>

        {{-- Empty State --}}
        <div x-show="selectedDates.length === 0"
            class="mb-4 p-4 rounded-lg border-2 border-dashed border-gray-200 dark:border-gray-700 text-center">
            <i class="fa-solid fa-hand-pointer text-gray-300 dark:text-gray-600 text-2xl mb-2"></i>
            <p class="text-xs text-gray-400 dark:text-gray-500">Belum ada tanggal dipilih. Drag di kalender untuk memilih.</p>
        </div>

        {{-- Manual Add Date --}}
        <div class="flex items-end gap-3 mb-4" x-show="selectedDates.length < 3">
            <div class="flex-1">
                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                    Tambah Tanggal Manual
                </label>
                <input type="date" x-model="manualDate" min="{{ date('Y-m-d') }}"
                    class="h-10 w-full rounded-lg border border-gray-300 bg-white dark:bg-gray-900 px-3 py-2 text-sm text-gray-800 dark:text-white focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 shadow-xs">
            </div>
            <button type="button" @click="addManualDate()"
                :disabled="!manualDate"
                class="h-10 px-4 rounded-lg bg-brand-500 hover:bg-brand-600 disabled:bg-gray-200 dark:disabled:bg-gray-700 text-white disabled:text-gray-400 text-sm font-medium transition-colors shadow-xs">
                <i class="fa-solid fa-plus mr-1"></i> Tambah
            </button>
        </div>

        {{-- Shared Time Inputs --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                    Jam Mulai <span class="text-red-500">*</span>
                </label>
                <input type="time" name="start_time" x-model="startTime" required
                    class="h-10 w-full rounded-lg border border-gray-300 bg-white dark:bg-gray-900 px-3 py-2 text-sm text-gray-800 dark:text-white focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 shadow-xs">
                @error('start_time') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                <p x-show="stepErrors.start_time" x-text="stepErrors.start_time" class="text-red-500 text-xs mt-1" style="display:none;"></p>
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                    Jam Selesai <span class="text-red-500">*</span>
                </label>
                <input type="time" name="end_time" x-model="endTime" required
                    class="h-10 w-full rounded-lg border border-gray-300 bg-white dark:bg-gray-900 px-3 py-2 text-sm text-gray-800 dark:text-white focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 shadow-xs">
                @error('end_time') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                <p x-show="stepErrors.end_time" x-text="stepErrors.end_time" class="text-red-500 text-xs mt-1" style="display:none;"></p>
            </div>
        </div>

        {{-- Selection Status Indicator --}}
        <template x-if="selectedDates.length > 0 && startTime && endTime">
            <div class="mt-3.5 pt-3 border-t border-brand-200/60 dark:border-brand-500/20 flex flex-wrap items-center justify-between gap-2 text-xs">
                <div class="flex items-center gap-2 text-brand-900 dark:text-brand-200 font-medium">
                    <i class="fa-solid fa-circle-check text-green-500"></i>
                    <span>
                        <span x-text="selectedDates.length"></span> hari terpilih,
                        pukul <b x-text="startTime"></b> - <b x-text="endTime"></b> WIB
                    </span>
                </div>
                <div class="text-gray-600 dark:text-gray-300 bg-white dark:bg-gray-800 px-2.5 py-1 rounded-md border border-gray-200 dark:border-gray-700 font-medium">
                    Durasi/hari: <span x-text="formattedDuration"></span>
                </div>
            </div>
        </template>
    </div>

    {{-- Hidden inputs for booking_dates[] --}}
    <template x-for="(date, index) in selectedDates" :key="'input-'+date">
        <input type="hidden" name="booking_dates[]" :value="date">
    </template>

    {{-- Step 2 Actions --}}
    <div class="mt-8 pt-6 border-t border-gray-100 dark:border-gray-700/60 flex items-center justify-between">
        <button type="button" @click="goToStep(1)"
            class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 px-5 py-2.5 rounded-lg text-sm font-medium transition-colors dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600 cursor-pointer">
            <i class="fa-solid fa-arrow-left text-sm"></i>
            <span>Kembali ke Detail (Step 1)</span>
        </button>
        <button type="button" @click="goToStep3()"
            class="inline-flex items-center gap-2 bg-brand-500 hover:bg-brand-600 text-white px-6 py-2.5 rounded-lg text-sm font-medium shadow-theme-xs transition-colors cursor-pointer">
            <span>Lanjut: Konfirmasi & Review (Step 3)</span>
            <i class="fa-solid fa-arrow-right text-sm"></i>
        </button>
    </div>

    <!-- Modal Detail Jadwal -->
    <div id="step2-event-modal" class="fixed inset-0 z-[99999] hidden bg-black/50" style="display:none;">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl w-full max-w-md overflow-hidden">
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Detail Pengajuan</h3>
                    <button onclick="document.getElementById('step2-event-modal').style.display='none'" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-800 dark:text-gray-200 mb-1">No. Booking</label>
                        <p class="text-sm font-normal text-gray-600 dark:text-gray-400 font-mono" id="modal-booking-number">-</p>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-800 dark:text-gray-200 mb-1">Nama Kegiatan</label>
                        <p class="text-sm font-normal text-gray-600 dark:text-gray-400" id="modal-activity-name">-</p>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-800 dark:text-gray-200 mb-1">Ruangan</label>
                            <p class="text-sm font-normal text-gray-600 dark:text-gray-400" id="modal-room-name">-</p>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-800 dark:text-gray-200 mb-1">Organisasi</label>
                            <p class="text-sm font-normal text-gray-600 dark:text-gray-400" id="modal-organization">-</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-800 dark:text-gray-200 mb-1">Tanggal & Waktu</label>
                            <p class="text-sm font-normal text-gray-600 dark:text-gray-400" id="modal-time-range">-</p>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-800 dark:text-gray-200 mb-1">Status</label>
                            <div class="mt-0.5" id="modal-status"></div>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-800 dark:text-gray-200 mb-1">Peserta</label>
                            <p class="text-sm font-normal text-gray-600 dark:text-gray-400" id="modal-participant-count">-</p>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-800 dark:text-gray-200 mb-1">PIC</label>
                            <p class="text-sm font-normal text-gray-600 dark:text-gray-400" id="modal-person-in-charge">-</p>
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-end px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                    <button type="button" onclick="document.getElementById('step2-event-modal').style.display='none'" class="inline-flex items-center gap-2 bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium transition-colors dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600">
                        <i class="fa-solid fa-xmark text-sm"></i> Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
