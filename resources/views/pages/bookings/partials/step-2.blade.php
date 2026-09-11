{{-- Step 2: Kalender Bulan & Pemilihan Tanggal --}}
<div x-show="step === 2" x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
    style="display:none;">
    <div
        class="mb-6 pb-4 border-b border-gray-100 dark:border-gray-700/60 flex flex-wrap items-center justify-between gap-4">
        <div>
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                <span
                    class="w-7 h-7 rounded-full bg-[#FFB800] text-white flex items-center justify-center text-xs font-bold">2</span>
                <span>Pilih Tanggal & Waktu Peminjaman</span>
            </h2>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 ml-9">
                Tarik (drag) pada kalender untuk memilih maksimal 3 hari sekaligus. Semua tanggal share jam yang sama.
            </p>
        </div>
    </div>

    {{-- Race Condition / Conflict Error Banner --}}
    @if (session('conflict_step') || $errors->has('schedule_conflict') || $errors->has('start_time'))
        <div
            class="mb-6 p-4 rounded-xl bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300 flex items-start gap-3">
            <div
                class="w-8 h-8 rounded-full bg-red-100 dark:bg-red-800/50 flex items-center justify-center shrink-0 text-red-600 dark:text-red-200 mt-0.5">
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
        <div
            class="mt-3 pt-3 border-t border-gray-200/70 dark:border-gray-700/60 flex flex-wrap items-center justify-between gap-2 text-xs">
            <div class="flex items-center gap-2 text-gray-600 dark:text-gray-400">
                <i class="fa-solid fa-lightbulb text-amber-500"></i>
                <span><b>Cara Memilih:</b> Klik & geser (drag) tanggal di kalender. Maks 3 hari.</span>
            </div>
            <div class="flex items-center gap-4 text-[11px]">
                <span class="inline-flex items-center gap-1.5 text-gray-600 dark:text-gray-400">
                    <span
                        class="w-3 h-3 rounded-xs bg-emerald-100 dark:bg-emerald-900/50 border border-emerald-400"></span>
                    Kosong
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
    <div
        class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden shadow-xs">
        <div id="step2-calendar"
            class="fc fc-media-screen fc-direction-ltr fc-theme-standard p-2 text-xs min-h-[420px]"></div>
    </div>

    {{-- Selected Dates & Time Summary Card (Displayed on Page) --}}
    <div
        class="mt-5 p-5 bg-brand-50/40 dark:bg-brand-500/5 rounded-xl border border-brand-200/80 dark:border-brand-500/20">
        <div class="flex flex-wrap items-center justify-between gap-3 mb-3">
            <h3 class="text-sm font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                <i class="fa-solid fa-calendar-check text-brand-500"></i>
                <span>Tanggal & Waktu Peminjaman</span>
                <span class="text-xs font-normal text-gray-500 dark:text-gray-400" x-show="totalDays > 0">
                    (<span x-text="totalDays"></span> hari dipilih)
                </span>
            </h3>
            <button type="button" @click="openScheduleModal()"
                class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-lg bg-brand-500 hover:bg-brand-600 text-white text-xs font-medium transition-colors shadow-xs cursor-pointer">
                <i class="fa-solid" :class="startDate ? 'fa-pen-to-square' : 'fa-calendar-plus'"></i>
                <span x-text="startDate ? 'Ubah Jadwal' : 'Atur Jadwal'"></span>
            </button>
        </div>

        {{-- Empty State --}}
        <div x-show="!startDate"
            class="p-5 rounded-lg border-2 border-dashed border-gray-200 dark:border-gray-700 text-center">
            <i class="fa-solid fa-hand-pointer text-gray-300 dark:text-gray-600 text-2xl mb-2"></i>
            <p class="text-xs text-gray-500 dark:text-gray-400">Belum ada tanggal dipilih. Klik atau geser rentang
                tanggal pada kalender di atas untuk memunculkan form jadwal peminjaman.</p>
        </div>

        {{-- Selected Schedule Preview --}}
        <div x-show="startDate" class="space-y-3">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3 text-xs">
                <div
                    class="p-3 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-2xs">
                    <span class="text-gray-400 dark:text-gray-500 block mb-0.5 font-medium text-[11px]">Tanggal
                        Mulai</span>
                    <p class="font-bold text-gray-800 dark:text-white" x-text="formatDateShort(startDate)"></p>
                </div>
                <div
                    class="p-3 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-2xs">
                    <span class="text-gray-400 dark:text-gray-500 block mb-0.5 font-medium text-[11px]">Tanggal
                        Akhir</span>
                    <p class="font-bold text-gray-800 dark:text-white" x-text="formatDateShort(endDate || startDate)">
                    </p>
                </div>
                <div
                    class="p-3 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-2xs">
                    <span class="text-gray-400 dark:text-gray-500 block mb-0.5 font-medium text-[11px]">Waktu
                        Pelaksanaan</span>
                    <p class="font-bold text-brand-600 dark:text-brand-400"
                        x-text="startTime && endTime ? (startTime + ' - ' + endTime + ' WIB') : 'Belum ditentukan'"></p>
                </div>
                <div
                    class="p-3 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-2xs">
                    <span class="text-gray-400 dark:text-gray-500 block mb-0.5 font-medium text-[11px]">Durasi /
                        Hari</span>
                    <p class="font-bold text-gray-800 dark:text-white" x-text="formattedDuration || '-'"></p>
                </div>
            </div>

            {{-- Selection Status Indicator --}}
            <template x-if="startDate && startTime && endTime">
                <div
                    class="pt-2 border-t border-brand-200/60 dark:border-brand-500/20 flex flex-wrap items-center justify-between gap-2 text-xs">
                    <div class="flex items-center gap-2 text-brand-900 dark:text-brand-200 font-medium">
                        <i class="fa-solid fa-circle-check text-green-500"></i>
                        <span>
                            <span x-text="totalDays"></span> hari peminjaman:
                            <b x-text="formattedDateRange"></b>, pukul <b x-text="startTime"></b> - <b
                                x-text="endTime"></b> WIB
                        </span>
                    </div>
                </div>
            </template>
        </div>
    </div>

    {{-- Hidden inputs for form submission --}}
    <input type="hidden" name="start_date" :value="startDate">
    <input type="hidden" name="end_date" :value="endDate || startDate">
    <input type="hidden" name="booking_date" :value="startDate">
    <template x-for="date in generatedDates" :key="'input-' + date">
        <input type="hidden" name="booking_dates[]" :value="date">
    </template>

    <!-- Modal Form Tanggal & Waktu Peminjaman -->
    <div x-show="showScheduleModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-[99999] overflow-y-auto" style="display:none;"
        @keydown.escape.window="closeScheduleModal()">

        <!-- Backdrop -->
        <div class="fixed inset-0 bg-gray-900/60 dark:bg-gray-950/80 backdrop-blur-xs transition-opacity"
            @click="closeScheduleModal()"></div>

        <!-- Modal Wrapper -->
        <div class="flex min-h-screen items-center justify-center p-4">
            <div class="relative w-full max-w-lg rounded-2xl bg-white dark:bg-gray-800 shadow-2xl border border-gray-100 dark:border-gray-700/60 overflow-hidden transform transition-all"
                x-show="showScheduleModal" x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                @click.outside="closeScheduleModal()">

                {{-- Modal Header --}}
                <div
                    class="flex items-center justify-between px-6 py-4 border-b border-gray-100 dark:border-gray-700/60 bg-brand-50/50 dark:bg-brand-900/10">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-9 h-9 rounded-xl bg-brand-500 text-white flex items-center justify-center shadow-xs">
                            <i class="fa-solid fa-calendar-days text-sm"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-gray-900 dark:text-white">
                                Tanggal & Waktu Peminjaman
                            </h3>
                            <p class="text-[11px] text-gray-500 dark:text-gray-400">
                                Atur rentang tanggal pelaksanaan dan jam peminjaman
                            </p>
                        </div>
                    </div>
                    <button type="button" @click="closeScheduleModal()"
                        class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 w-8 h-8 rounded-lg flex items-center justify-center hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors cursor-pointer">
                        <i class="fa-solid fa-xmark text-base"></i>
                    </button>
                </div>

                {{-- Modal Body --}}
                <div class="p-6 space-y-4">
                    {{-- 2 Tanggal Inputs: Tanggal Mulai & Tanggal Akhir --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-800 dark:text-gray-200 mb-2">
                            Tanggal Peminjaman
                        </label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-[11px] font-medium text-gray-600 dark:text-gray-400 mb-1">
                                    Tanggal Mulai <span class="text-red-500">*</span>
                                </label>
                                <input type="date" x-model="startDate" min="{{ date('Y-m-d') }}"
                                    @change="if (!endDate || endDate < startDate) endDate = startDate"
                                    class="h-10 w-full rounded-lg border border-gray-300 bg-white dark:bg-gray-900 px-3 py-2 text-sm text-gray-800 dark:text-white focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 shadow-xs">
                                <p x-show="stepErrors.start_date" x-text="stepErrors.start_date"
                                    class="text-red-500 text-[11px] mt-1" style="display:none;"></p>
                            </div>

                            <div>
                                <label class="block text-[11px] font-medium text-gray-600 dark:text-gray-400 mb-1">
                                    Tanggal Akhir <span class="text-red-500">*</span>
                                </label>
                                <input type="date" x-model="endDate" :min="startDate || '{{ date('Y-m-d') }}'"
                                    class="h-10 w-full rounded-lg border border-gray-300 bg-white dark:bg-gray-900 px-3 py-2 text-sm text-gray-800 dark:text-white focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 shadow-xs">
                                <p x-show="stepErrors.end_date" x-text="stepErrors.end_date"
                                    class="text-red-500 text-[11px] mt-1" style="display:none;"></p>
                            </div>
                        </div>

                        {{-- Teks Bantuan Rentang Hari --}}
                        <div class="mt-2 text-xs flex items-center justify-between text-gray-500 dark:text-gray-400">
                            <span>
                                <span x-show="startDate && endDate">
                                    Total: <b class="text-brand-600 dark:text-brand-400"
                                        x-text="totalDays + ' hari'"></b>
                                    <span x-show="totalDays > 0" class="text-[11px] text-gray-400">
                                        (<span x-text="formattedDateRange"></span>)
                                    </span>
                                </span>
                            </span>
                            <span class="text-[11px] text-amber-600 dark:text-amber-400 font-medium">
                                <i class="fa-solid fa-info-circle mr-0.5"></i> Maks. 3 hari
                            </span>
                        </div>
                    </div>

                    {{-- Divider --}}
                    <div class="border-t border-gray-100 dark:border-gray-700/60"></div>

                    {{-- 2 Jam Inputs: Jam Mulai & Jam Selesai --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-800 dark:text-gray-200 mb-2">
                            Waktu / Jam (Berlaku setiap hari peminjaman)
                        </label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-[11px] font-medium text-gray-600 dark:text-gray-400 mb-1">
                                    Jam Mulai <span class="text-red-500">*</span>
                                </label>
                                <input type="time" name="start_time" x-model="startTime" required
                                    class="h-10 w-full rounded-lg border border-gray-300 bg-white dark:bg-gray-900 px-3 py-2 text-sm text-gray-800 dark:text-white focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 shadow-xs">
                                @error('start_time')
                                    <p class="text-red-500 text-[11px] mt-1">{{ $message }}</p>
                                @enderror
                                <p x-show="stepErrors.start_time" x-text="stepErrors.start_time"
                                    class="text-red-500 text-[11px] mt-1" style="display:none;"></p>
                            </div>

                            <div>
                                <label class="block text-[11px] font-medium text-gray-600 dark:text-gray-400 mb-1">
                                    Jam Selesai <span class="text-red-500">*</span>
                                </label>
                                <input type="time" name="end_time" x-model="endTime" required
                                    class="h-10 w-full rounded-lg border border-gray-300 bg-white dark:bg-gray-900 px-3 py-2 text-sm text-gray-800 dark:text-white focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 shadow-xs">
                                @error('end_time')
                                    <p class="text-red-500 text-[11px] mt-1">{{ $message }}</p>
                                @enderror
                                <p x-show="stepErrors.end_time" x-text="stepErrors.end_time"
                                    class="text-red-500 text-[11px] mt-1" style="display:none;"></p>
                            </div>
                        </div>

                        {{-- Jam Operasional & Durasi Info --}}
                        <div
                            class="mt-2 flex flex-wrap items-center justify-between gap-2 text-xs text-gray-500 dark:text-gray-400">
                            <span class="text-[11px]">
                                Jam operasional: <span class="font-medium text-gray-700 dark:text-gray-300"
                                    x-text="roomOpenTime + ' - ' + roomCloseTime"></span>
                            </span>
                            <span x-show="startTime && endTime"
                                class="text-[11px] font-semibold text-brand-600 dark:text-brand-400">
                                Durasi/hari: <span x-text="formattedDuration"></span>
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Modal Footer --}}
                <div
                    class="flex items-center justify-end gap-3 px-6 py-4 border-t border-gray-100 dark:border-gray-700/60 bg-gray-50/50 dark:bg-gray-900/30">
                    <button type="button" @click="closeScheduleModal()"
                        class="px-4 py-2 rounded-lg bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-xs font-semibold transition-colors cursor-pointer">
                        Batal
                    </button>
                    <button type="button" @click="saveScheduleModal()"
                        class="px-5 py-2 rounded-lg bg-brand-500 hover:bg-brand-600 text-white text-xs font-semibold shadow-xs transition-colors flex items-center gap-1.5 cursor-pointer">
                        <i class="fa-solid fa-check text-xs"></i>
                        <span>Simpan & Terapkan Jadwal</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

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
                    <button onclick="document.getElementById('step2-event-modal').style.display='none'"
                        class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-800 dark:text-gray-200 mb-1">No.
                            Booking</label>
                        <p class="text-sm font-normal text-gray-600 dark:text-gray-400 font-mono"
                            id="modal-booking-number">-</p>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-800 dark:text-gray-200 mb-1">Nama
                            Kegiatan</label>
                        <p class="text-sm font-normal text-gray-600 dark:text-gray-400" id="modal-activity-name">-</p>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label
                                class="block text-xs font-bold text-gray-800 dark:text-gray-200 mb-1">Ruangan</label>
                            <p class="text-sm font-normal text-gray-600 dark:text-gray-400" id="modal-room-name">-</p>
                        </div>
                        <div>
                            <label
                                class="block text-xs font-bold text-gray-800 dark:text-gray-200 mb-1">Organisasi</label>
                            <p class="text-sm font-normal text-gray-600 dark:text-gray-400" id="modal-organization">-
                            </p>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-800 dark:text-gray-200 mb-1">Tanggal &
                                Waktu</label>
                            <p class="text-sm font-normal text-gray-600 dark:text-gray-400" id="modal-time-range">-
                            </p>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-800 dark:text-gray-200 mb-1">Status</label>
                            <div class="mt-0.5" id="modal-status"></div>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label
                                class="block text-xs font-bold text-gray-800 dark:text-gray-200 mb-1">Peserta</label>
                            <p class="text-sm font-normal text-gray-600 dark:text-gray-400"
                                id="modal-participant-count">-</p>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-800 dark:text-gray-200 mb-1">PIC</label>
                            <p class="text-sm font-normal text-gray-600 dark:text-gray-400"
                                id="modal-person-in-charge">-</p>
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-end px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                    <button type="button" onclick="document.getElementById('step2-event-modal').style.display='none'"
                        class="inline-flex items-center gap-2 bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium transition-colors dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600">
                        <i class="fa-solid fa-xmark text-sm"></i> Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
