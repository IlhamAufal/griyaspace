@extends('layouts.app')

@section('title', 'Kalender')

@section('content')
<div x-data="calendarApp()" x-init="init()">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Kalender</h1>
        <p class="text-sm text-gray-500 mt-1">Lihat dan kelola jadwal ruangan</p>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-4">
        <div class="flex flex-wrap items-center gap-4">
            <div class="flex items-center gap-2">
                <button @click="prev()" class="p-2 rounded-lg hover:bg-gray-100 transition-colors text-gray-600" title="Sebelumnya">
                    <i class="fa-solid fa-chevron-left text-base"></i>
                </button>
                <button @click="goToday()" class="px-3 py-1.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                    Hari Ini
                </button>
                <button @click="next()" class="p-2 rounded-lg hover:bg-gray-100 transition-colors text-gray-600" title="Selanjutnya">
                    <i class="fa-solid fa-chevron-right text-base"></i>
                </button>
            </div>

            <h2 class="text-lg font-semibold text-gray-700" x-text="currentTitle"></h2>

            <div class="ml-auto flex items-center gap-3">
                <select x-model="selectedRoom" @change="updateEvents()"
                    class="text-sm border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-brand-500 focus:border-brand-500 outline-none">
                    <option value="">Semua Ruangan</option>
                    @foreach($rooms as $room)
                        <option value="{{ $room->id }}">{{ $room->name }}</option>
                    @endforeach
                </select>

                <div class="flex rounded-lg border border-gray-300 overflow-hidden">
                    <button @click="changeView('timeGridDay')" :class="currentView === 'timeGridDay' ? 'bg-brand-500 text-white' : 'bg-white text-gray-700 hover:bg-gray-50'"
                        class="px-3 py-1.5 text-sm font-medium transition-colors">
                        Hari
                    </button>
                    <button @click="changeView('timeGridWeek')" :class="currentView === 'timeGridWeek' ? 'bg-brand-500 text-white' : 'bg-white text-gray-700 hover:bg-gray-50'"
                        class="px-3 py-1.5 text-sm font-medium border-l border-gray-300 transition-colors">
                        Minggu
                    </button>
                    <button @click="changeView('dayGridMonth')" :class="currentView === 'dayGridMonth' ? 'bg-brand-500 text-white' : 'bg-white text-gray-700 hover:bg-gray-50'"
                        class="px-3 py-1.5 text-sm font-medium border-l border-gray-300 transition-colors">
                        Bulan
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
        <div id="calendar" class="fc fc-media-screen fc-direction-ltr fc-theme-standard"></div>
    </div>

    <!-- Modal Detail Jadwal -->
    <x-common.modal id="calendar-event-modal" title="Detail Pengajuan" maxWidth="md">
        <div class="space-y-4">
            <div>
                <label class="block text-xs font-bold text-gray-800 dark:text-gray-200 mb-1">No. Booking</label>
                <p class="text-sm font-normal text-gray-600 dark:text-gray-400 font-mono" x-text="modalData.booking_number || '-'"></p>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-800 dark:text-gray-200 mb-1">Nama Kegiatan</label>
                <p class="text-sm font-normal text-gray-600 dark:text-gray-400" x-text="modalData.activity_name || '-'"></p>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-800 dark:text-gray-200 mb-1">Ruangan</label>
                    <p class="text-sm font-normal text-gray-600 dark:text-gray-400" x-text="modalData.room_name || '-'"></p>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-800 dark:text-gray-200 mb-1">Organisasi</label>
                    <p class="text-sm font-normal text-gray-600 dark:text-gray-400" x-text="modalData.organization || '-'"></p>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-800 dark:text-gray-200 mb-1">Tanggal & Waktu</label>
                    <p class="text-sm font-normal text-gray-600 dark:text-gray-400" x-text="modalData.time_range || '-'"></p>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-800 dark:text-gray-200 mb-1">Status</label>
                    <div class="mt-0.5">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-normal"
                            :class="{
                                'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400': modalData.status === 'approved',
                                'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400': modalData.status === 'submitted',
                                'bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400': modalData.status === 'revision',
                                'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400': modalData.status === 'rejected',
                                'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300': !['approved','submitted','revision','rejected'].includes(modalData.status)
                            }"
                            x-text="modalData.status_label || '-'"></span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-800 dark:text-gray-200 mb-1">Peserta</label>
                    <p class="text-sm font-normal text-gray-600 dark:text-gray-400" x-text="(modalData.participant_count || '-') + ' orang'"></p>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-800 dark:text-gray-200 mb-1">PIC</label>
                    <p class="text-sm font-normal text-gray-600 dark:text-gray-400" x-text="modalData.person_in_charge || '-'"></p>
                </div>
            </div>
        </div>

        <x-slot:footer>
            @if (auth()->user()->isAdmin() || auth()->user()->isStaff())
                <a :href="modalData.booking_id ? '/pengajuan/' + modalData.booking_id : '#'"
                    class="inline-flex items-center gap-2 bg-brand-500 hover:bg-brand-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                    <i class="fa-solid fa-eye text-xs"></i> Lihat Detail
                </a>
            @endif
            <button type="button" @click="close()" class="inline-flex items-center gap-2 bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium transition-colors dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600">
                <i class="fa-solid fa-xmark text-sm"></i>
                <span>Tutup</span>
            </button>
        </x-slot:footer>
    </x-common.modal>
</div>
@endsection

@push('scripts')
<script>
    function calendarApp() {
        return {
            calendar: null,
            currentTitle: '',
            currentView: 'timeGridWeek',
            selectedRoom: '',

            init() {
                const self = this;

                this.calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
                    plugins: [
                        FullCalendar.dayGridPlugin,
                        FullCalendar.timeGridPlugin,
                        FullCalendar.interactionPlugin,
                        FullCalendar.listPlugin
                    ],
                    initialView: 'timeGridWeek',
                    headerToolbar: false,
                    slotMinTime: '06:00:00',
                    slotMaxTime: '22:00:00',
                    slotDuration: '00:30:00',
                    slotLabelInterval: '01:00',
                    slotLabelFormat: {
                        hour: '2-digit',
                        minute: '2-digit',
                        hour12: false
                    },
                    eventTimeFormat: {
                        hour: '2-digit',
                        minute: '2-digit',
                        hour12: false
                    },
                    allDaySlot: false,
                    nowIndicator: true,
                    selectable: true,
                    editable: false,
                    locale: 'id',
                    height: 'auto',
                    contentHeight: 600,
                    events: function(info, successCallback, failureCallback) {
                        self.fetchEvents(info.startStr, info.endStr, successCallback, failureCallback);
                    },
                    datesSet: function(dateInfo) {
                        self.currentTitle = dateInfo.view.title;
                        self.currentView = dateInfo.view.type;
                    },
                    eventClick: function(info) {
                        self.showEventDetail(info.event);
                    }
                });

                this.calendar.render();
            },

            async fetchEvents(start, end, successCallback, failureCallback) {
                try {
                    const params = new URLSearchParams({
                        from: start,
                        to: end,
                    });

                    if (this.selectedRoom) {
                        params.append('room_id', this.selectedRoom);
                    }

                    const response = await fetch(`/kalender/events?${params.toString()}`, {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                    });

                    if (!response.ok) {
                        throw new Error('Gagal memuat data kalender');
                    }

                    const data = await response.json();
                    successCallback(data);
                } catch (error) {
                    console.error('Error fetching events:', error);
                    failureCallback(error);
                }
            },

            updateEvents() {
                if (this.calendar) {
                    this.calendar.refetchEvents();
                }
            },

            prev() {
                this.calendar.prev();
            },

            next() {
                this.calendar.next();
            },

            goToday() {
                this.calendar.today();
            },

            changeView(view) {
                this.calendar.changeView(view);
            },

            showEventDetail(event) {
                const props = event.extendedProps || {};
                const statusLabels = {
                    'approved': 'Disetujui',
                    'submitted': 'Diajukan',
                    'revision': 'Revisi',
                    'rejected': 'Ditolak',
                    'cancelled': 'Dibatalkan'
                };
                const timeRange = props.start_time && props.end_time
                    ? `${props.booking_date || ''} (${props.start_time} - ${props.end_time} WIB)`
                    : (event.start ? event.start.toLocaleString('id-ID', { date: 'numeric', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit', hour12: false }) : '-');

                window.dispatchEvent(new CustomEvent('open-modal', {
                    detail: {
                        id: 'calendar-event-modal',
                        data: {
                            booking_id: event.id,
                            booking_number: props.booking_number || '-',
                            activity_name: props.activity_name || event.title || '-',
                            room_name: props.room || '-',
                            organization: props.organization || '-',
                            time_range: timeRange,
                            status: props.status || '',
                            status_label: statusLabels[props.status] || props.status || '-',
                            participant_count: props.participant_count || '-',
                            person_in_charge: props.person_in_charge || '-',
                        }
                    }
                }));
            }
        };
    }
</script>
@endpush
