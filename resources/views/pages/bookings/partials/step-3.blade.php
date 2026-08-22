{{-- Step 3: Konfirmasi & Review Keseluruhan Data Sebelum Submit --}}
<div x-show="step === 3" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" style="display:none;">
    <div class="mb-6 pb-4 border-b border-gray-100 dark:border-gray-700/60">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
            <span class="w-7 h-7 rounded-full bg-[#1CBDB3] text-white flex items-center justify-center text-xs font-bold">3</span>
            <span>Konfirmasi & Review Pengajuan</span>
        </h2>
        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 ml-9">
            Mohon periksa kembali seluruh data peminjaman di bawah ini sebelum mengirimkan pengajuan.
        </p>
    </div>

    {{-- Review Cards Grid --}}
    <div class="space-y-5">
        {{-- 2x1 Grid: Ruangan & Pemohon + Jadwal & Waktu Peminjaman --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            {{-- Section 1: Ruangan & Pemohon --}}
            <div class="rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden shadow-xs flex flex-col">
                <div class="bg-[#2F3185] px-5 py-3 flex items-center justify-between text-white">
                    <h3 class="text-sm font-semibold tracking-wide">
                        Ruangan & Pemohon
                    </h3>
                    <button type="button" @click="goToStep(1)" title="Ubah Ruangan & Pemohon"
                        class="text-white/80 hover:text-white p-1.5 rounded-lg hover:bg-white/10 transition-colors flex items-center justify-center">
                        <i class="fa-solid fa-pen-to-square text-sm"></i>
                    </button>
                </div>

                <div class="p-5 bg-white dark:bg-gray-800 flex-1 space-y-3.5 text-xs">
                    <div>
                        <span class="text-gray-500 dark:text-gray-400 block mb-1 font-medium">Ruangan Terpilih</span>
                        <p class="font-bold text-gray-900 dark:text-white text-sm" x-text="roomName || '-'"></p>
                        <p class="text-gray-500 text-[11px] mt-0.5" x-text="roomLocation"></p>
                    </div>
                    <div class="pt-2.5 border-t border-gray-100 dark:border-gray-700/60 grid grid-cols-2 gap-3">
                        <div>
                            <span class="text-gray-500 dark:text-gray-400 block mb-1 font-medium">Organisasi / Ormawa</span>
                            <p class="font-semibold text-gray-900 dark:text-white" x-text="organizationName || 'Pengaju Mandiri'"></p>
                        </div>
                        <div>
                            <span class="text-gray-500 dark:text-gray-400 block mb-1 font-medium">Kapasitas Ruangan</span>
                            <p class="font-semibold text-gray-900 dark:text-white"><span x-text="roomCapacity || '0'"></span> orang</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Section 2: Jadwal & Waktu Peminjaman --}}
            <div class="rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden shadow-xs flex flex-col">
                <div class="bg-[#2F3185] px-5 py-3 flex items-center justify-between text-white">
                    <h3 class="text-sm font-semibold tracking-wide">
                        Jadwal & Waktu Peminjaman
                    </h3>
                    <button type="button" @click="goToStep(2)" title="Ubah Jadwal & Waktu"
                        class="text-white/80 hover:text-white p-1.5 rounded-lg hover:bg-white/10 transition-colors flex items-center justify-center">
                        <i class="fa-solid fa-pen-to-square text-sm"></i>
                    </button>
                </div>

                <div class="p-5 bg-white dark:bg-gray-800 flex-1 space-y-3.5 text-xs">
                    <div>
                        <span class="text-gray-500 dark:text-gray-400 block mb-1 font-medium">Tanggal Pelaksanaan</span>
                        <p class="font-bold text-gray-900 dark:text-white text-sm" x-text="formattedDateIndo || '-'"></p>
                        <p class="text-gray-500 text-[11px] mt-0.5" x-text="bookingDate"></p>
                    </div>
                    <div class="pt-2.5 border-t border-gray-100 dark:border-gray-700/60 grid grid-cols-2 gap-3">
                        <div>
                            <span class="text-gray-500 dark:text-gray-400 block mb-1 font-medium">Waktu / Jam</span>
                            <p class="font-bold text-brand-600 dark:text-brand-400 text-sm">
                                <span x-text="startTime"></span> - <span x-text="endTime"></span> WIB
                            </p>
                        </div>
                        <div>
                            <span class="text-gray-500 dark:text-gray-400 block mb-1 font-medium">Total Durasi</span>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 font-semibold text-gray-800 dark:text-gray-200" x-text="formattedDuration"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Section 3: Detail Kegiatan & PIC --}}
        <div class="rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden shadow-xs">
            <div class="bg-[#2F3185] px-5 py-3 flex items-center justify-between text-white">
                <h3 class="text-sm font-semibold tracking-wide">
                    Informasi Kegiatan & Penanggung Jawab
                </h3>
                <button type="button" @click="goToStep(1)" title="Ubah Informasi Kegiatan"
                    class="text-white/80 hover:text-white p-1.5 rounded-lg hover:bg-white/10 transition-colors flex items-center justify-center">
                    <i class="fa-solid fa-pen-to-square text-sm"></i>
                </button>
            </div>

            <div class="p-5 bg-white dark:bg-gray-800">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 text-xs">
                    <div class="sm:col-span-2 md:col-span-3">
                        <span class="text-gray-500 dark:text-gray-400 block mb-1 font-medium">Nama Kegiatan</span>
                        <p class="font-bold text-gray-900 dark:text-white text-sm" x-text="activityName || '-'"></p>
                    </div>
                    <div class="sm:col-span-2 md:col-span-3">
                        <span class="text-gray-500 dark:text-gray-400 block mb-1 font-medium">Tujuan Kegiatan</span>
                        <p class="text-gray-800 dark:text-gray-200 leading-relaxed bg-gray-50 dark:bg-gray-900/50 p-3 rounded-lg border border-gray-200 dark:border-gray-700" x-text="purpose || '-'"></p>
                    </div>
                    <div>
                        <span class="text-gray-500 dark:text-gray-400 block mb-1 font-medium">Jumlah Peserta</span>
                        <p class="font-semibold text-gray-900 dark:text-white">
                            <span x-text="participantCount || '0'"></span> orang
                            <span class="text-green-600 dark:text-green-400 text-[11px] font-normal" x-show="participantCount <= roomCapacity">(Sesuai Kapasitas)</span>
                        </p>
                    </div>
                    <div>
                        <span class="text-gray-500 dark:text-gray-400 block mb-1 font-medium">Penanggung Jawab (PIC)</span>
                        <p class="font-semibold text-gray-900 dark:text-white" x-text="personInCharge || '-'"></p>
                    </div>
                    <div>
                        <span class="text-gray-500 dark:text-gray-400 block mb-1 font-medium">No. Telepon / WhatsApp</span>
                        <p class="font-semibold text-gray-900 dark:text-white" x-text="contactPhone || '-'"></p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Section 4: Dokumen Lampiran --}}
        <div class="rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden shadow-xs">
            <div class="bg-[#2F3185] px-5 py-3 flex items-center justify-between text-white">
                <h3 class="text-sm font-semibold tracking-wide">
                    Dokumen Lampiran
                </h3>
                <button type="button" @click="goToStep(1)" title="Ubah Dokumen"
                    class="text-white/80 hover:text-white p-1.5 rounded-lg hover:bg-white/10 transition-colors flex items-center justify-center">
                    <i class="fa-solid fa-pen-to-square text-sm"></i>
                </button>
            </div>

            <div class="p-5 bg-white dark:bg-gray-800">
                <div class="text-xs">
                    <span class="text-gray-500 dark:text-gray-400 block mb-1.5 font-medium">Dokumen Surat Permohonan (PDF)</span>
                    <div class="flex items-center gap-3 p-3 rounded-lg bg-gray-50 dark:bg-gray-900/50 border border-gray-200 dark:border-gray-700 max-w-md">
                        <div class="w-9 h-9 rounded-lg bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400 flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-file-pdf text-lg"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="font-medium text-gray-900 dark:text-white truncate" x-text="documentFileName || 'File PDF Terlampir'"></p>
                            <p class="text-[11px] text-gray-400 mt-0.5" x-text="documentFileSize"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Terms & Confirmation Checkbox --}}
        <div class="p-4 rounded-xl bg-brand-50/40 dark:bg-brand-500/5 border border-brand-200 dark:border-brand-500/20">
            <label class="flex items-start gap-3 cursor-pointer">
                <input type="checkbox" x-model="agreedToTerms" class="mt-1 h-4 w-4 rounded border-gray-300 text-brand-600 focus:ring-brand-500 cursor-pointer">
                <span class="text-xs text-gray-700 dark:text-gray-300 leading-relaxed">
                    Saya menyatakan bahwa data dan jadwal yang diisikan sudah benar serta bersedia mematuhi semua tata tertib dan ketentuan penggunaan fasilitas ruangan yang berlaku.
                </span>
            </label>
        </div>
    </div>

    {{-- Step 3 Actions (Submit Button) --}}
    <div class="mt-8 pt-6 border-t border-gray-100 dark:border-gray-700/60 flex items-center justify-between">
        <button type="button" @click="goToStep(2)"
            class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 px-5 py-2.5 rounded-lg text-sm font-medium transition-colors dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600 cursor-pointer">
            <i class="fa-solid fa-arrow-left text-sm"></i>
            <span>Kembali ke Jadwal (Step 2)</span>
        </button>

        <button type="submit" :disabled="!agreedToTerms || isSubmitting"
            @click="isSubmitting = true"
            :class="!agreedToTerms ? 'opacity-50 cursor-not-allowed bg-brand-400' : 'bg-brand-500 hover:bg-brand-600 cursor-pointer'"
            class="inline-flex items-center gap-2 text-white px-7 py-2.5 rounded-lg text-sm font-semibold shadow-theme-xs transition-colors">
            <template x-if="!isSubmitting">
                <i class="fa-solid fa-paper-plane text-sm"></i>
            </template>
            <template x-if="isSubmitting">
                <i class="fa-solid fa-spinner fa-spin text-sm"></i>
            </template>
            <span x-text="isSubmitting ? 'Memproses Pengajuan...' : 'Kirim Pengajuan'"></span>
        </button>
    </div>
</div>
