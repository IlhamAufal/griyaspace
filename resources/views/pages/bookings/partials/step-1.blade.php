{{-- Step 1: Detail Pengajuan (Organisasi, Ruangan, Kegiatan, PIC, Dokumen PDF) --}}
<div x-show="step === 1" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
    <div class="mb-6 pb-4 border-b border-gray-100 dark:border-gray-700/60">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
            <span class="w-7 h-7 rounded-full bg-brand-500 text-white flex items-center justify-center text-xs font-bold">1</span>
            <span>Detail Peminjaman & Informasi Kegiatan</span>
        </h2>
        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 ml-9">
            Lengkapi data pemohon, organisasi, ruangan, kegiatan, penanggung jawab, dan lampiran surat permohonan.
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5">
        {{-- 2x2 Grid Item 1: Pengajuan Untuk Pengguna --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Pengajuan Untuk Pengguna
            </label>
            @if($user->isAdmin() && $users->count())
                <select name="user_id" x-model="selectedUserId" @change="onUserChange()"
                    class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 shadow-theme-xs">
                    <option value="">Pilih Pengguna / Akun Pemohon</option>
                    @foreach($users as $u)
                    @if(!$u->isAdmin())
                    <option value="{{ $u->id }}" data-org="{{ $u->organization->name ?? '' }}" data-org-id="{{ $u->organization_id ?? '' }}">
                        {{ $u->name }} ({{ $u->email }}) {{ $u->organization ? ' - ' . $u->organization->name : '' }}
                    </option>
                    @endif
                    @endforeach
                </select>
                @error('user_id') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
            @else
                <div class="h-11 w-full flex items-center justify-between rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 px-4 py-2.5 text-sm text-gray-800 dark:text-gray-200">
                    <div class="flex items-center gap-2 truncate">
                        <i class="fa-solid fa-user text-brand-500"></i>
                        <span class="font-medium truncate">{{ $user->name }}</span>
                        <span class="text-gray-400 text-xs truncate">({{ $user->email }})</span>
                    </div>
                    <span class="text-xs px-2 py-0.5 rounded-full bg-brand-50 text-brand-700 dark:bg-brand-900/30 dark:text-brand-300 font-medium shrink-0">Akun Anda</span>
                </div>
            @endif
        </div>

        {{-- 2x2 Grid Item 2: Organisasi / Ormawa --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Organisasi / Ormawa
            </label>
            @if($user->isOrganization() && $user->organization)
                <div class="h-11 w-full flex items-center justify-between rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 px-4 py-2.5 text-sm text-gray-800 dark:text-gray-200">
                    <div class="flex items-center gap-2 truncate">
                        <i class="fa-solid fa-building text-brand-500"></i>
                        <span class="font-medium truncate">{{ $user->organization->name }} ({{ $user->organization->abbreviation ?? 'Organisasi' }})</span>
                    </div>
                    <span class="text-xs px-2 py-0.5 rounded-full bg-brand-50 text-brand-700 dark:bg-brand-900/30 dark:text-brand-300 font-medium shrink-0">Terdaftar</span>
                </div>
                <input type="hidden" name="organization_id" value="{{ $user->organization_id }}">
            @else
                <div class="h-11 w-full flex items-center rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 px-4 py-2.5 text-sm text-gray-800 dark:text-gray-200">
                    <i class="fa-solid fa-building text-gray-400 mr-2"></i>
                    <span class="truncate" x-text="organizationName || 'Organisasi otomatis terisi'"></span>
                </div>
            @endif
        </div>

        {{-- 2x2 Grid Item 3: Pilih Ruangan --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Pilih Ruangan <span class="text-red-500">*</span>
            </label>
            <select name="room_id" x-model="roomId" @change="onRoomChange()" required
                class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 shadow-theme-xs">
                <option value="">-- Pilih Ruangan --</option>
                @foreach($rooms as $room)
                <option value="{{ $room->id }}"
                    data-name="{{ $room->name }}"
                    data-capacity="{{ $room->capacity }}"
                    data-location="{{ $room->location }}"
                    data-open="{{ $room->open_time ?? '06:00' }}"
                    data-close="{{ $room->close_time ?? '22:00' }}"
                    {{ old('room_id') == $room->id ? 'selected' : '' }}>
                    {{ $room->name }} (Kapasitas: {{ $room->capacity }} orang)
                </option>
                @endforeach
            </select>
            @error('room_id') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
            <p x-show="stepErrors.room_id" x-text="stepErrors.room_id" class="text-red-500 text-xs mt-1.5" style="display:none;"></p>

        </div>

        {{-- 2x2 Grid Item 4: Nama Kegiatan --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Nama Kegiatan <span class="text-red-500">*</span>
            </label>
            <input type="text" name="activity_name" x-model="activityName" value="{{ old('activity_name') }}"
                placeholder="Contoh: Seminar Nasional Teknologi & AI 2026"
                class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 shadow-theme-xs" required>
            @error('activity_name') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
            <p x-show="stepErrors.activity_name" x-text="stepErrors.activity_name" class="text-red-500 text-xs mt-1.5" style="display:none;"></p>
        </div>

        {{-- Tujuan Kegiatan (Non-required, no red asterisk) --}}
        <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Deskripsi Kegiatan
            </label>
            <textarea name="purpose" x-model="purpose" rows="3"
                placeholder="Jelaskan deskripsi dan tujuan singkat peminjaman ruangan (opsional)..."
                class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-3 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 shadow-theme-xs">{{ old('purpose') }}</textarea>
            @error('purpose') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
        </div>

        {{-- Jumlah Peserta --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Jumlah Peserta <span class="text-red-500">*</span>
            </label>
            <div class="relative">
                <input type="number" name="participant_count" x-model="participantCount" min="1" value="{{ old('participant_count') }}"
                    placeholder="Contoh: 30"
                    class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 shadow-theme-xs" required>
            </div>
            <template x-if="roomCapacity && participantCount > roomCapacity">
                <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    Jumlah peserta melebihi kapasitas ruangan (<span x-text="roomCapacity"></span> orang).
                </p>
            </template>
            @error('participant_count') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
            <p x-show="stepErrors.participant_count" x-text="stepErrors.participant_count" class="text-red-500 text-xs mt-1.5" style="display:none;"></p>
        </div>

        {{-- Penanggung Jawab (PIC) --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Penanggung Jawab (PIC) <span class="text-red-500">*</span>
            </label>
            <input type="text" name="person_in_charge" x-model="personInCharge" value="{{ old('person_in_charge') }}"
                placeholder="Nama lengkap penanggung jawab"
                class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 shadow-theme-xs" required>
            @error('person_in_charge') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
            <p x-show="stepErrors.person_in_charge" x-text="stepErrors.person_in_charge" class="text-red-500 text-xs mt-1.5" style="display:none;"></p>
        </div>

        {{-- Kontak / No. WhatsApp --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                No. Telepon / WhatsApp <span class="text-red-500">*</span>
            </label>
            <input type="tel" name="contact_phone" x-model="contactPhone"
                @input="contactPhone = $event.target.value.replace(/[^0-9]/g, '')"
                inputmode="numeric"
                pattern="[0-9]*"
                oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                value="{{ old('contact_phone') }}"
                placeholder="Contoh: 081234567890"
                class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 shadow-theme-xs" required>
            @error('contact_phone') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
            <p x-show="stepErrors.contact_phone" x-text="stepErrors.contact_phone" class="text-red-500 text-xs mt-1.5" style="display:none;"></p>
        </div>

        {{-- Upload PDF Dokumen Surat Permohonan --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Dokumen Surat Permohonan (PDF) <span class="text-red-500">*</span>
            </label>
            <div class="relative">
                <input type="file" id="document-input" name="document" accept=".pdf" @change="onFileChange($event)"
                    class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 file:mr-4 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100 dark:file:bg-brand-900/30 dark:file:text-brand-300 shadow-theme-xs">
            </div>
            <template x-if="documentFileName">
                <p class="text-xs text-green-600 dark:text-green-400 mt-1.5 flex items-center gap-1.5">
                    <i class="fa-solid fa-file-pdf"></i>
                    <span x-text="documentFileName"></span>
                    <span class="text-gray-400" x-text="'(' + documentFileSize + ')'"></span>
                </p>
            </template>
            @error('document') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
            <p x-show="stepErrors.document" x-text="stepErrors.document" class="text-red-500 text-xs mt-1.5" style="display:none;"></p>
        </div>
    </div>

    {{-- Step 1 Actions --}}
    <div class="mt-8 pt-6 border-t border-gray-100 dark:border-gray-700/60 flex items-center justify-between">
        <a href="{{ route('bookings.index') }}"
            class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 px-5 py-2.5 rounded-lg text-sm font-medium transition-colors dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600">
            <i class="fa-solid fa-xmark text-sm"></i>
            <span>Batal</span>
        </a>
        <button type="button" @click="goToStep2()"
            class="inline-flex items-center gap-2 bg-brand-500 hover:bg-brand-600 text-white px-6 py-2.5 rounded-lg text-sm font-medium shadow-theme-xs transition-colors cursor-pointer">
            <span>Lanjut: Pilih Jadwal & Kalender</span>
            <i class="fa-solid fa-arrow-right text-sm"></i>
        </button>
    </div>
</div>
