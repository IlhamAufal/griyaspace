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
                    <button @click="changeView('timeGridWeek')" :class="currentView === 'timeGridWeek' ? 'bg-brand-500 text-white' : 'bg-white text-gray-700 hover:bg-gray-50'"
                        class="px-3 py-1.5 text-sm font-medium transition-colors">
                        Minggu
                    </button>
                    <button @click="changeView('dayGridMonth')" :class="currentView === 'dayGridMonth' ? 'bg-brand-500 text-white' : 'bg-white text-gray-700 hover:bg-gray-50'"
                        class="px-3 py-1.5 text-sm font-medium border-l border-gray-300 transition-colors">
                        Bulan
                    </button>
                    <button @click="changeView('listWeek')" :class="currentView === 'listWeek' ? 'bg-brand-500 text-white' : 'bg-white text-gray-700 hover:bg-gray-50'"
                        class="px-3 py-1.5 text-sm font-medium border-l border-gray-300 transition-colors">
                        Daftar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
        <div id="calendar" class="fc fc-media-screen fc-direction-ltr fc-theme-standard"></div>
    </div>

    <!-- Modal Detail Jadwal -->
    <x-common.modal id="calendar-event-modal" title="Detail Jadwal Ruangan" icon="fa-solid fa-calendar-days" maxWidth="md">
        <div class="space-y-4">
            <div>
                <label class="text-xs font-medium text-gray-400 uppercase tracking-wider">Nama Kegiatan</label>
                <p class="text-base font-semibold text-gray-900 dark:text-white mt-0.5" x-text="modalData.title || '-'"></p>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="text-xs font-medium text-gray-400 uppercase tracking-wider">Ruangan</label>
                    <p class="text-sm font-medium text-gray-800 dark:text-gray-200 mt-0.5 flex items-center gap-1.5">
                        <i class="fa-solid fa-door-open text-brand-500 text-xs"></i>
                        <span x-text="modalData.room_name || '-'"></span>
                    </p>
                </div>
                <div>
                    <label class="text-xs font-medium text-gray-400 uppercase tracking-wider">Peminjam / Tamu</label>
                    <p class="text-sm font-medium text-gray-800 dark:text-gray-200 mt-0.5 flex items-center gap-1.5">
                        <i class="fa-solid fa-user text-brand-500 text-xs"></i>
                        <span x-text="modalData.guest_name || '-'"></span>
                    </p>
                </div>
            </div>

            <template x-if="modalData.status">
                <div>
                    <label class="text-xs font-medium text-gray-400 uppercase tracking-wider">Status</label>
                    <div class="mt-1">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-brand-50 dark:bg-brand-500/10 text-brand-600 dark:text-brand-400" x-text="modalData.status"></span>
                    </div>
                </div>
            </template>
        </div>

        <x-slot:footer>
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
    import { Calendar } from '@fullcalendar/core';
    import dayGridPlugin from '@fullcalendar/daygrid';
    import timeGridPlugin from '@fullcalendar/timegrid';
    import interactionPlugin from '@fullcalendar/interaction';
    import listPlugin from '@fullcalendar/list';

    function calendarApp() {
        return {
            calendar: null,
            currentTitle: '',
            currentView: 'timeGridWeek',
            selectedRoom: '',

            init() {
                const self = this;

                this.calendar = new Calendar(document.getElementById('calendar'), {
                    plugins: [dayGridPlugin, timeGridPlugin, interactionPlugin, listPlugin],
                    initialView: 'timeGridWeek',
                    headerToolbar: false,
                    slotMinTime: '06:00:00',
                    slotMaxTime: '22:00:00',
                    slotDuration: '00:30:00',
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

                    const response = await fetch(`/kalender?${params.toString()}`, {
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
                window.dispatchEvent(new CustomEvent('open-modal', {
                    detail: {
                        id: 'calendar-event-modal',
                        data: {
                            title: event.title,
                            room_name: props.room_name || '-',
                            guest_name: props.guest_name || '-',
                            status: props.status || ''
                        }
                    }
                }));
            }
        };
    }
</script>
@endpush
