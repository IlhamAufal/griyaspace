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
                <button @click="prev()" class="p-2 rounded-lg hover:bg-gray-100 transition-colors" title="Sebelumnya">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>
                <button @click="goToday()" class="px-3 py-1.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                    Hari Ini
                </button>
                <button @click="next()" class="p-2 rounded-lg hover:bg-gray-100 transition-colors" title="Selanjutnya">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
            </div>

            <h2 class="text-lg font-semibold text-gray-700" x-text="currentTitle"></h2>

            <div class="ml-auto flex items-center gap-3">
                <select x-model="selectedRoom" @change="updateEvents()"
                    class="text-sm border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                    <option value="">Semua Ruangan</option>
                    @foreach($rooms as $room)
                        <option value="{{ $room->id }}">{{ $room->name }}</option>
                    @endforeach
                </select>

                <div class="flex rounded-lg border border-gray-300 overflow-hidden">
                    <button @click="changeView('timeGridWeek')" :class="currentView === 'timeGridWeek' ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50'"
                        class="px-3 py-1.5 text-sm font-medium transition-colors">
                        Minggu
                    </button>
                    <button @click="changeView('dayGridMonth')" :class="currentView === 'dayGridMonth' ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50'"
                        class="px-3 py-1.5 text-sm font-medium border-l border-gray-300 transition-colors">
                        Bulan
                    </button>
                    <button @click="changeView('listWeek')" :class="currentView === 'listWeek' ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50'"
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
                const props = event.extendedProps;
                const message = [
                    `Acara: ${event.title}`,
                    `Ruangan: ${props.room_name || '-'}`,
                    props.guest_name ? `Tamu: ${props.guest_name}` : '',
                    props.status ? `Status: ${props.status}` : '',
                ].filter(Boolean).join('\n');

                alert(message);
            }
        };
    }
</script>
@endpush
