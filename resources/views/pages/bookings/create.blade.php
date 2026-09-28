@extends('layouts.app')

@section('content')
<div class="py-6" x-data="bookingWizard()" x-init="init()">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Breadcrumb & Title --}}
        <div class="mb-6">
            <nav class="flex items-center gap-2 text-xs text-gray-500 mb-2">
                <a href="{{ route('dashboard') }}" class="hover:text-brand-600 transition-colors"><i class="fa-solid fa-house"></i></a>
                <i class="fa-solid fa-chevron-right text-[10px]"></i>
                <a href="{{ route('bookings.index') }}" class="hover:text-brand-600 transition-colors">Pengajuan Peminjaman</a>
                <i class="fa-solid fa-chevron-right text-[10px]"></i>
                <span class="text-gray-900 dark:text-white font-medium">Pengajuan Baru</span>
            </nav>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Form Pengajuan Peminjaman Ruangan</h1>
        </div>

        {{-- Stepper Progress Bar --}}
        {{-- Stepper Progress Bar (Clean & Simpel Sesuai Gambar) --}}
        <div class="mb-8">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                
                {{-- Step 1 Indicator --}}
                <button type="button" @click="goToStep(1)"
                    class="w-full flex items-center gap-3.5 px-4 py-3 rounded-2xl transition-all cursor-pointer text-left shadow-xs"
                    :class="step === 1 
                        ? 'bg-[#2F3185] text-white shadow-md' 
                        : (step > 1 ? 'bg-success-600 text-white' : 'bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400')">
                    <!-- Circle Angka / Icon -->
                    <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center font-bold text-base shrink-0 shadow-xs"
                        :class="step === 1 ? 'text-[#2F3185]' : (step > 1 ? 'text-success-600' : 'text-gray-400 dark:text-gray-500')">
                        <template x-if="step > 1">
                            <i class="fa-solid fa-check text-sm"></i>
                        </template>
                        <template x-if="step <= 1">
                            <span>1</span>
                        </template>
                    </div>
                    <!-- Judul Langkah -->
                    <span class="text-base sm:text-lg font-bold tracking-tight truncate">
                        Detail & Ruangan
                    </span>
                </button>

                {{-- Step 2 Indicator --}}
                <button type="button" @click="goToStep(2)"
                    class="w-full flex items-center gap-3.5 px-4 py-3 rounded-2xl transition-all cursor-pointer text-left shadow-xs"
                    :class="step === 2 
                        ? 'bg-[#2F3185] text-white shadow-md' 
                        : (step > 2 ? 'bg-success-600 text-white' : 'bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400')">
                    <!-- Circle Angka / Icon -->
                    <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center font-bold text-base shrink-0 shadow-xs"
                        :class="step === 2 ? 'text-[#2F3185]' : (step > 2 ? 'text-success-600' : 'text-gray-400 dark:text-gray-500')">
                        <template x-if="step > 2">
                            <i class="fa-solid fa-check text-sm"></i>
                        </template>
                        <template x-if="step <= 2">
                            <span>2</span>
                        </template>
                    </div>
                    <!-- Judul Langkah -->
                    <span class="text-base sm:text-lg font-bold tracking-tight truncate">
                        Jadwal & Kalender
                    </span>
                </button>

                {{-- Step 3 Indicator --}}
                <button type="button" @click="goToStep(3)"
                    class="w-full flex items-center gap-3.5 px-4 py-3 rounded-2xl transition-all cursor-pointer text-left shadow-xs"
                    :class="step === 3 
                        ? 'bg-[#2F3185] text-white shadow-md' 
                        : 'bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400'">
                    <!-- Circle Angka / Icon -->
                    <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center font-bold text-base shrink-0 shadow-xs"
                        :class="step === 3 ? 'text-[#2F3185]' : 'text-gray-400 dark:text-gray-500'">
                        <span>3</span>
                    </div>
                    <!-- Judul Langkah -->
                    <span class="text-base sm:text-lg font-bold tracking-tight truncate">
                        Konfirmasi & Submit
                    </span>
                </button>

            </div>
        </div>

        {{-- Form Container --}}
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-2xl border border-gray-200 dark:border-gray-700/60 p-6 sm:p-8">
            <form id="booking-form" action="{{ route('bookings.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- Include Step 1 Partial: Detail --}}
                @include('pages.bookings.partials.step-1')

                {{-- Include Step 2 Partial: FullCalendar timeGridDay & Schedule --}}
                @include('pages.bookings.partials.step-2')

                {{-- Include Step 3 Partial: Confirmation & Submit --}}
                @include('pages.bookings.partials.step-3')
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function bookingWizard() {
    return {
        // Step State (defaults to 2 if conflict returned from server)
        step: {{ session('conflict_step') ? 2 : (old('room_id') && ($errors->has('start_time') || $errors->has('end_time') || $errors->has('booking_date') || $errors->has('booking_dates') || $errors->has('start_date') || $errors->has('end_date') || $errors->has('schedule_conflict')) ? 2 : 1) }},

        // Reactive Form Fields
        roomId: '{{ old('room_id', '') }}',
        roomName: '',
        roomCode: '',
        roomCapacity: 0,
        roomLocation: '',
        roomOpenTime: '06:00',
        roomCloseTime: '22:00',
        roomsData: @json($rooms),

        selectedUserId: '{{ old('user_id', '') }}',
        organizationName: '{{ $user->isOrganization() && $user->organization ? $user->organization->name : '' }}',

        activityName: '{{ old('activity_name', '') }}',
        purpose: @json(old('purpose', '')),
        participantCount: '{{ old('participant_count', '') }}',
        personInCharge: '{{ old('person_in_charge', '') }}',
        contactPhone: '{{ old('contact_phone', '') }}',

        startDate: '{{ old('start_date', old('booking_date', is_array(old('booking_dates')) && count(old('booking_dates')) > 0 ? old('booking_dates')[0] : '')) }}',
        endDate: '{{ old('end_date', old('booking_date', is_array(old('booking_dates')) && count(old('booking_dates')) > 0 ? end(old('booking_dates')) : '')) }}',
        calendarTitle: '',
        startTime: '{{ old('start_time', '') }}',
        endTime: '{{ old('end_time', '') }}',
        showScheduleModal: false,

        documentFileName: '',
        documentFileSize: '',

        agreedToTerms: false,
        isSubmitting: false,
        stepErrors: {},

        // Calendar variables
        calendar: null,
        calendarViewMode: 'dayGridMonth',

        init() {
            if (this.roomId) {
                this.updateSelectedRoomData();
            }

            if (this.step === 2) {
                this.$nextTick(() => {
                    setTimeout(() => this.initOrRefreshCalendar(), 200);
                });
            }
        },

        updateSelectedRoomData() {
            const found = this.roomsData.find(r => String(r.id) === String(this.roomId));
            if (found) {
                this.roomName = found.name;
                this.roomCode = found.code;
                this.roomCapacity = found.capacity;
                this.roomLocation = found.location || 'Gedung Utama';
                this.roomOpenTime = (found.open_time || '06:00:00').substring(0, 5);
                this.roomCloseTime = (found.close_time || '22:00:00').substring(0, 5);
            }
        },

        onRoomChange() {
            this.updateSelectedRoomData();
            if (this.calendar) {
                this.calendar.refetchEvents();
            }
        },

        onUserChange() {
            const selectEl = document.querySelector('select[name="user_id"]');
            if (selectEl && selectEl.selectedIndex > 0) {
                const opt = selectEl.options[selectEl.selectedIndex];
                const org = opt.getAttribute('data-org');
                if (org) {
                    this.organizationName = org;
                }
            }
        },

        onFileChange(e) {
            const file = e.target.files[0];
            if (file) {
                this.documentFileName = file.name;
                this.documentFileSize = (file.size / (1024 * 1024)).toFixed(2) + ' MB';
            } else {
                this.documentFileName = '';
                this.documentFileSize = '';
            }
        },

        // Step 1 Validation & Proceed
        goToStep2() {
            this.stepErrors = {};

            if (!this.roomId) {
                this.stepErrors.room_id = 'Silakan pilih ruangan terlebih dahulu.';
            }

            if (!this.activityName || !this.activityName.trim()) {
                this.stepErrors.activity_name = 'Nama kegiatan wajib diisi.';
            }

            if (!this.participantCount || parseInt(this.participantCount) < 1) {
                this.stepErrors.participant_count = 'Jumlah peserta minimal 1 orang.';
            } else if (this.roomCapacity && parseInt(this.participantCount) > parseInt(this.roomCapacity)) {
                this.stepErrors.participant_count = `Jumlah peserta (${this.participantCount}) melebihi kapasitas ruangan (${this.roomCapacity} orang).`;
            }

            if (!this.personInCharge || !this.personInCharge.trim()) {
                this.stepErrors.person_in_charge = 'Penanggung jawab (PIC) wajib diisi.';
            }

            if (!this.contactPhone || !this.contactPhone.trim()) {
                this.stepErrors.contact_phone = 'No. telepon / WhatsApp wajib diisi.';
            }

            const fileInput = document.getElementById('document-input');
            if (!this.documentFileName && (!fileInput || !fileInput.files.length)) {
                this.stepErrors.document = 'Surat permohonan peminjaman ruangan (PDF) wajib dilampirkan.';
            }

            if (Object.keys(this.stepErrors).length > 0) {
                window.scrollTo({ top: 200, behavior: 'smooth' });
                return;
            }

            this.step = 2;
            this.$nextTick(() => {
                setTimeout(() => this.initOrRefreshCalendar(), 150);
            });
            window.scrollTo({ top: 100, behavior: 'smooth' });
        },

        // FullCalendar Setup & Lifecycle
        initOrRefreshCalendar() {
            const calendarEl = document.getElementById('step2-calendar');
            if (!calendarEl) return;

            const self = this;

            if (!this.calendar) {
                this.calendar = new FullCalendar.Calendar(calendarEl, {
                    plugins: [
                        FullCalendar.dayGridPlugin,
                        FullCalendar.interactionPlugin
                    ],
                    initialView: 'dayGridMonth',
                    headerToolbar: false,
                    validRange: {
                        start: new Date().toISOString().split('T')[0]
                    },
                    selectable: true,
                    selectMirror: true,
                    selectOverlap: false,
                    unselectAuto: false,
                    locale: 'id',
                    height: 'auto',
                    contentHeight: 420,
                    fixedWeekCount: false,
                    showNonCurrentDates: false,

                    events: function(info, successCallback, failureCallback) {
                        self.fetchRoomEvents(info.startStr, info.endStr, successCallback, failureCallback);
                    },

                    select: function(selectionInfo) {
                        self.handleDateSelect(selectionInfo);
                    },

                    eventClick: function(info) {
                        info.jsEvent.preventDefault();
                        self.showEventDetail(info.event);
                    },

                    datesSet: function(dateInfo) {
                        const d = dateInfo.view.currentStart;
                        const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                        self.calendarTitle = `${months[d.getMonth()]} ${d.getFullYear()}`;
                    }
                });

                this.calendar.render();

                if (this.startDate) {
                    this.calendar.gotoDate(this.startDate);
                }
            } else {
                this.calendar.updateSize();
                this.calendar.refetchEvents();
                if (this.startDate) {
                    this.calendar.gotoDate(this.startDate);
                }
            }
        },

        async fetchRoomEvents(start, end, successCallback, failureCallback) {
            try {
                const params = new URLSearchParams({
                    from: start,
                    to: end,
                });

                if (this.roomId) {
                    params.append('room_id', this.roomId);
                }

                const response = await fetch(`{{ route('calendar.events') }}?${params.toString()}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    }
                });

                if (!response.ok) {
                    throw new Error('Gagal memuat jadwal kalender');
                }

                const data = await response.json();
                successCallback(data);
            } catch (err) {
                console.error('Fetch events error:', err);
                failureCallback(err);
            }
        },

        handleDateSelect(selectionInfo) {
            const startStr = selectionInfo.startStr.substring(0, 10);
            const endStr = selectionInfo.endStr.substring(0, 10);

            const startDate = new Date(startStr + 'T00:00:00');
            const endExclusive = new Date(endStr + 'T00:00:00');
            const endInclusive = new Date(endExclusive.getTime() - 86400000);
            let endDateStr = endInclusive.toISOString().substring(0, 10);

            const diffDays = Math.round((endInclusive - startDate) / 86400000) + 1;
            if (diffDays > 3) {
                const maxEnd = new Date(startDate.getTime() + (2 * 86400000));
                endDateStr = maxEnd.toISOString().substring(0, 10);
            }

            this.startDate = startStr;
            this.endDate = endDateStr;

            delete this.stepErrors.start_date;
            delete this.stepErrors.end_date;
            delete this.stepErrors.booking_dates;

            if (this.calendar) {
                this.calendar.unselect();
            }

            this.openScheduleModal();
        },

        openScheduleModal() {
            if (!this.startDate) {
                const today = new Date().toISOString().substring(0, 10);
                this.startDate = today;
                this.endDate = today;
            } else if (!this.endDate) {
                this.endDate = this.startDate;
            }
            this.showScheduleModal = true;
            document.body.classList.add('overflow-hidden');
        },

        closeScheduleModal() {
            this.showScheduleModal = false;
            document.body.classList.remove('overflow-hidden');
        },

        saveScheduleModal() {
            this.stepErrors = {};

            if (!this.startDate) {
                this.stepErrors.start_date = 'Tanggal mulai wajib dipilih.';
            } else {
                const today = new Date();
                today.setHours(0, 0, 0, 0);
                if (new Date(this.startDate + 'T00:00:00') < today) {
                    this.stepErrors.start_date = 'Tanggal mulai tidak boleh di masa lalu.';
                }
            }

            if (!this.endDate) {
                this.stepErrors.end_date = 'Tanggal akhir wajib dipilih.';
            } else if (this.startDate && this.endDate < this.startDate) {
                this.stepErrors.end_date = 'Tanggal akhir tidak boleh lebih awal dari tanggal mulai.';
            } else if (this.totalDays > 3) {
                this.stepErrors.end_date = 'Maksimal durasi peminjaman adalah 3 hari.';
            }

            if (!this.startTime) {
                this.stepErrors.start_time = 'Jam mulai wajib diisi.';
            }

            if (!this.endTime) {
                this.stepErrors.end_time = 'Jam selesai wajib diisi.';
            } else if (this.startTime && this.endTime <= this.startTime) {
                this.stepErrors.end_time = 'Jam selesai harus lebih besar dari jam mulai.';
            }

            if (Object.keys(this.stepErrors).length > 0) {
                return;
            }

            this.closeScheduleModal();
        },

        calendarPrev() {
            if (this.calendar) {
                this.calendar.prev();
            }
        },

        calendarNext() {
            if (this.calendar) {
                this.calendar.next();
            }
        },

        calendarToday() {
            if (this.calendar) {
                this.calendar.today();
            }
        },

        refreshCalendar() {
            if (this.calendar) {
                this.calendar.refetchEvents();
            }
        },

        changeCalendarView(viewName) {
            this.calendarViewMode = viewName;
            if (this.calendar) {
                this.calendar.changeView(viewName);
            }
        },

        // Step 2 Validation & Proceed to Step 3
        goToStep3() {
            this.stepErrors = {};

            if (!this.startDate) {
                this.stepErrors.start_date = 'Tanggal mulai wajib dipilih.';
            } else {
                const today = new Date();
                today.setHours(0, 0, 0, 0);
                if (new Date(this.startDate + 'T00:00:00') < today) {
                    this.stepErrors.start_date = 'Tanggal mulai tidak boleh di masa lalu.';
                }
            }

            if (!this.endDate) {
                this.stepErrors.end_date = 'Tanggal akhir wajib dipilih.';
            } else if (this.startDate && this.endDate < this.startDate) {
                this.stepErrors.end_date = 'Tanggal akhir tidak boleh lebih awal dari tanggal mulai.';
            } else if (this.totalDays > 3) {
                this.stepErrors.end_date = 'Maksimal durasi peminjaman adalah 3 hari.';
            }

            if (!this.startTime) {
                this.stepErrors.start_time = 'Jam mulai wajib diisi.';
            }

            if (!this.endTime) {
                this.stepErrors.end_time = 'Jam selesai wajib diisi.';
            } else if (this.startTime && this.endTime <= this.startTime) {
                this.stepErrors.end_time = 'Jam selesai harus lebih besar dari jam mulai.';
            }

            if (Object.keys(this.stepErrors).length > 0) {
                this.openScheduleModal();
                return;
            }

            this.step = 3;
            window.scrollTo({ top: 100, behavior: 'smooth' });
        },

        goToStep(targetStep) {
            if (targetStep === 2) {
                this.goToStep2();
                return;
            }
            if (targetStep === 3) {
                this.goToStep3();
                return;
            }
            this.step = targetStep;
            window.scrollTo({ top: 100, behavior: 'smooth' });
        },

        submitBooking() {
            if (!this.agreedToTerms || this.isSubmitting) return;
            this.isSubmitting = true;
            this.$nextTick(() => {
                document.getElementById('booking-form').submit();
            });
        },

        // Helper Getters
        get totalDays() {
            if (!this.startDate || !this.endDate) return 0;
            const start = new Date(this.startDate + 'T00:00:00');
            const end = new Date(this.endDate + 'T00:00:00');
            if (end < start) return 0;
            const diffTime = end.getTime() - start.getTime();
            return Math.round(diffTime / (1000 * 60 * 60 * 24)) + 1;
        },

        get generatedDates() {
            if (!this.startDate || !this.endDate) return [];
            const dates = [];
            let current = new Date(this.startDate + 'T00:00:00');
            const end = new Date(this.endDate + 'T00:00:00');
            if (end < current) return [];
            while (current <= end) {
                dates.push(current.toISOString().substring(0, 10));
                current.setDate(current.getDate() + 1);
            }
            return dates;
        },

        formatDateShort(dateStr) {
            if (!dateStr) return '-';
            const d = new Date(dateStr + 'T00:00:00');
            const days = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];
            const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
            return `${days[d.getDay()]}, ${d.getDate()} ${months[d.getMonth()]} ${d.getFullYear()}`;
        },

        get formattedDateRange() {
            if (!this.startDate) return '-';
            if (!this.endDate || this.startDate === this.endDate) {
                return this.formatDateShort(this.startDate);
            }
            return `${this.formatDateShort(this.startDate)} s/d ${this.formatDateShort(this.endDate)}`;
        },

        get formattedDateIndo() {
            if (!this.startDate) return '-';
            if (!this.endDate || this.startDate === this.endDate) {
                return this.formatDateShort(this.startDate);
            }
            return `${this.formatDateShort(this.startDate)} - ${this.formatDateShort(this.endDate)} (${this.totalDays} hari)`;
        },

        get formattedDuration() {
            if (!this.startTime || !this.endTime) return '-';
            const [startH, startM] = this.startTime.split(':').map(Number);
            const [endH, endM] = this.endTime.split(':').map(Number);

            const startTotal = startH * 60 + startM;
            const endTotal = endH * 60 + endM;
            const diff = endTotal - startTotal;

            if (diff <= 0) return '0 Menit';

            const hours = Math.floor(diff / 60);
            const mins = diff % 60;

            if (hours > 0 && mins > 0) {
                return `${hours} Jam ${mins} Menit`;
            } else if (hours > 0) {
                return `${hours} Jam`;
            } else {
                return `${mins} Menit`;
            }
        },

        showEventDetail(event) {
            const props = event.extendedProps || {};
            const statusLabels = @json(\App\Enums\BookingStatus::labels());
            const timeRange = props.start_time && props.end_time
                ? `${props.booking_date || ''} (${props.start_time} - ${props.end_time} WIB)`
                : (event.start ? event.start.toLocaleString('id-ID', { date: 'numeric', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit', hour12: false }) : '-');

            document.getElementById('modal-booking-number').textContent = props.booking_number || '-';
            document.getElementById('modal-activity-name').textContent = props.activity_name || event.title || '-';
            document.getElementById('modal-room-name').textContent = props.room || '-';
            document.getElementById('modal-organization').textContent = props.organization || '-';
            document.getElementById('modal-time-range').textContent = timeRange;
            document.getElementById('modal-participant-count').textContent = (props.participant_count || '-') + ' orang';
            document.getElementById('modal-person-in-charge').textContent = props.person_in_charge || '-';

            const statusEl = document.getElementById('modal-status');
            const statusClass = @json(\App\Enums\BookingStatus::colors());
            const cls = statusClass[props.status] || 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300';
            statusEl.innerHTML = `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-normal ${cls}">${statusLabels[props.status] || props.status || '-'}</span>`;

            const link = document.getElementById('modal-link');
            link.href = '/pengajuan/' + event.id;

            document.getElementById('step2-event-modal').style.display = 'block';
        }
    };
}
</script>
@endpush
