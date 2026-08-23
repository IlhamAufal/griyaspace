{{-- Step 2: FullCalendar timeGridDay & Pemilihan Waktu --}}
<div x-show="step === 2" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" style="display:none;">
    <div class="mb-6 pb-4 border-b border-gray-100 dark:border-gray-700/60 flex flex-wrap items-center justify-between gap-4">
        <div>
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                <span class="w-7 h-7 rounded-full bg-[#FFB800] text-white flex items-center justify-center text-xs font-bold">2</span>
                <span>Pilih Tanggal & Waktu (Kalender Interaktif)</span>
            </h2>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 ml-9">
                Lihat slot jadwal yang terisi/kosong. Tarik (drag) pada area kosong kalender untuk mengisi waktu otomatis.
            </p>
        </div>

        {{-- Room Badge & Change Button --}}
        <div class="flex items-center gap-3 bg-brand-50/70 dark:bg-brand-500/10 px-4 py-2 rounded-xl border border-brand-200/60 dark:border-brand-500/20">
            <div>
                <div class="text-xs font-semibold text-brand-900 dark:text-brand-200 flex items-center gap-1.5">
                    <i class="fa-solid fa-door-open text-brand-600 dark:text-brand-400"></i>
                    <span x-text="roomName || 'Ruangan Terpilih'"></span>
                </div>
                <div class="text-[11px] text-gray-500 dark:text-gray-400" x-text="roomLocation + ' &bull; Kapasitas ' + roomCapacity + ' org'"></div>
            </div>
            <button type="button" @click="goToStep(1)" class="text-xs font-medium text-brand-600 hover:text-brand-700 dark:text-brand-400 underline ml-2">
                Ubah
            </button>
        </div>
    </div>

    {{-- Race Condition / Conflict Error Banner --}}
    @if(session('conflict_step') || $errors->has('schedule_conflict') || $errors->has('start_time'))
    <div class="mb-6 p-4 rounded-xl bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300 flex items-start gap-3">
        <div class="w-8 h-8 rounded-full bg-red-100 dark:bg-red-800/50 flex items-center justify-center shrink-0 text-red-600 dark:text-red-200 mt-0.5">
            <i class="fa-solid fa-triangle-exclamation text-base"></i>
        </div>
        <div class="text-sm">
            <h4 class="font-bold text-red-800 dark:text-red-200">Terjadi Bentrok Jadwal (Race Condition)!</h4>
            <p class="mt-0.5 text-xs text-red-600 dark:text-red-300">
                {{ $errors->first('schedule_conflict') ?: $errors->first('start_time') ?: 'Slot jadwal yang Anda pilih baru saja dipesan oleh pengguna lain.' }}
                Kalender telah diperbarui dengan jadwal terkini. Silakan pilih slot waktu kosong lainnya pada kalender di bawah.
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
                    class="p-2 rounded-lg bg-white dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200 border border-gray-200 dark:border-gray-700 text-xs font-medium transition-colors shadow-xs" title="Hari Sebelumnya">
                    <i class="fa-solid fa-chevron-left mr-1"></i> Sebelumnya
                </button>
                <button type="button" @click="calendarToday()"
                    class="px-3 py-2 rounded-lg bg-white dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200 border border-gray-200 dark:border-gray-700 text-xs font-medium transition-colors shadow-xs">
                    Hari Ini
                </button>
                <button type="button" @click="calendarNext()"
                    class="p-2 rounded-lg bg-white dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200 border border-gray-200 dark:border-gray-700 text-xs font-medium transition-colors shadow-xs" title="Hari Selanjutnya">
                    Selanjutnya <i class="fa-solid fa-chevron-right ml-1"></i>
                </button>
            </div>

            {{-- Date Direct Picker & Indonesian Date Display --}}
            <div class="flex items-center gap-2">
                <div class="flex items-center gap-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg px-3 py-1.5 shadow-xs">
                    <i class="fa-solid fa-calendar-day text-brand-500 text-xs"></i>
                    <input type="date" x-model="bookingDate" @change="onBookingDateInputChange()" min="{{ date('Y-m-d') }}"
                        class="text-xs bg-transparent border-0 focus:ring-0 text-gray-800 dark:text-gray-200 p-0 font-medium cursor-pointer">
                </div>
                <span class="text-xs font-semibold text-gray-700 dark:text-gray-300 hidden sm:inline" x-text="formattedDateIndo"></span>
            </div>

            {{-- View Selector & Refresh --}}
            <div class="flex items-center gap-2">
                <div class="flex rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden bg-white dark:bg-gray-800 text-xs font-medium shadow-xs">
                    <button type="button" @click="changeCalendarView('timeGridDay')"
                        :class="calendarViewMode === 'timeGridDay' ? 'bg-brand-500 text-white' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700'"
                        class="px-3 py-2 transition-colors">
                        Hari
                    </button>
                    <button type="button" @click="changeCalendarView('timeGridWeek')"
                        :class="calendarViewMode === 'timeGridWeek' ? 'bg-brand-500 text-white' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700'"
                        class="px-3 py-2 border-l border-gray-200 dark:border-gray-700 transition-colors">
                        Minggu
                    </button>
                    <button type="button" @click="changeCalendarView('dayGridMonth')"
                        :class="calendarViewMode === 'dayGridMonth' ? 'bg-brand-500 text-white' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700'"
                        class="px-3 py-2 border-l border-gray-200 dark:border-gray-700 transition-colors">
                        Bulan
                    </button>
                </div>
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
                <span><b>Cara Memilih:</b> Klik & geser (drag) slot waktu kosong di kalender untuk mengisi jam secara otomatis.</span>
            </div>
            <div class="flex items-center gap-4 text-[11px]">
                <span class="inline-flex items-center gap-1.5 text-gray-600 dark:text-gray-400">
                    <span class="w-3 h-3 rounded-xs bg-emerald-100 dark:bg-emerald-900/50 border border-emerald-400"></span> Slot Kosong
                </span>
                <span class="inline-flex items-center gap-1.5 text-gray-600 dark:text-gray-400">
                    <span class="w-3 h-3 rounded-xs bg-red-400"></span> Slot Terisi (Bentrok)
                </span>
                <span class="inline-flex items-center gap-1.5 text-gray-600 dark:text-gray-400">
                    <span class="w-3 h-3 rounded-xs bg-brand-500"></span> Pilihan Anda
                </span>
            </div>
        </div>
    </div>

    {{-- Interactive Calendar Container --}}
    <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden shadow-xs">
        <div id="step2-calendar" class="fc fc-media-screen fc-direction-ltr fc-theme-standard p-2 text-xs min-h-[460px]"></div>
    </div>

    {{-- Selected Time Form Inputs (Auto-filled via Drag, and Manually Adjustable) --}}
    <div class="mt-5 p-5 bg-brand-50/40 dark:bg-brand-500/5 rounded-xl border border-brand-200/80 dark:border-brand-500/20">
        <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-3 flex items-center gap-2">
            <i class="fa-solid fa-clock text-brand-500"></i>
            <span>Waktu Peminjaman yang Dipilih</span>
        </h3>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                    Tanggal Kegiatan <span class="text-red-500">*</span>
                </label>
                <input type="date" name="booking_date" x-model="bookingDate" @change="onBookingDateInputChange()" min="{{ date('Y-m-d') }}" required
                    class="h-10 w-full rounded-lg border border-gray-300 bg-white dark:bg-gray-900 px-3 py-2 text-sm text-gray-800 dark:text-white focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 shadow-xs">
                @error('booking_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                <p x-show="stepErrors.booking_date" x-text="stepErrors.booking_date" class="text-red-500 text-xs mt-1" style="display:none;"></p>
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                    Jam Mulai <span class="text-red-500">*</span>
                </label>
                <input type="time" name="start_time" x-model="startTime" @change="onTimeInputChange()" required
                    class="h-10 w-full rounded-lg border border-gray-300 bg-white dark:bg-gray-900 px-3 py-2 text-sm text-gray-800 dark:text-white focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 shadow-xs">
                @error('start_time') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                <p x-show="stepErrors.start_time" x-text="stepErrors.start_time" class="text-red-500 text-xs mt-1" style="display:none;"></p>
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                    Jam Selesai <span class="text-red-500">*</span>
                </label>
                <input type="time" name="end_time" x-model="endTime" @change="onTimeInputChange()" required
                    class="h-10 w-full rounded-lg border border-gray-300 bg-white dark:bg-gray-900 px-3 py-2 text-sm text-gray-800 dark:text-white focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 shadow-xs">
                @error('end_time') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                <p x-show="stepErrors.end_time" x-text="stepErrors.end_time" class="text-red-500 text-xs mt-1" style="display:none;"></p>
            </div>
        </div>

        {{-- Selection Status Indicator --}}
        <template x-if="bookingDate && startTime && endTime">
            <div class="mt-3.5 pt-3 border-t border-brand-200/60 dark:border-brand-500/20 flex flex-wrap items-center justify-between gap-2 text-xs">
                <div class="flex items-center gap-2 text-brand-900 dark:text-brand-200 font-medium">
                    <i class="fa-solid fa-circle-check text-green-500"></i>
                    <span>Jadwal Terpilih: <b x-text="formattedDateIndo"></b> pukul <b x-text="startTime"></b> - <b x-text="endTime"></b> WIB</span>
                </div>
                <div class="text-gray-600 dark:text-gray-300 bg-white dark:bg-gray-800 px-2.5 py-1 rounded-md border border-gray-200 dark:border-gray-700 font-medium">
                    Durasi: <span x-text="formattedDuration"></span>
                </div>
            </div>
        </template>
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
</div>
