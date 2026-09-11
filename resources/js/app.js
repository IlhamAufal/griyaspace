import './bootstrap';
import Alpine from 'alpinejs';
import ApexCharts from 'apexcharts';
import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import interactionPlugin from '@fullcalendar/interaction';
import listPlugin from '@fullcalendar/list';
import flatpickr from 'flatpickr';
import 'flatpickr/dist/flatpickr.min.css';

window.ApexCharts = ApexCharts;

window.FullCalendar = {
    Calendar,
    dayGridPlugin,
    timeGridPlugin,
    interactionPlugin,
    listPlugin,
};

window.Alpine = Alpine;

// Register session timer component via Alpine.data
Alpine.data('sessionTimer', () => {
    const meta = document.querySelector('meta[name="afk-timeout"]');
    const total = meta ? parseInt(meta.content, 10) : 1800;

    return {
        total: total,
        remaining: total,
        expanded: false,
        timerInterval: null,
        debounceTimer: null,

        get isWarning() {
            return this.remaining <= 300 && this.remaining > 60;
        },

        get isDanger() {
            return this.remaining <= 60;
        },

        init() {
            this.timerInterval = setInterval(() => {
                this.remaining--;

                if (this.remaining <= 0) {
                    this.logout();
                }
            }, 1000);
        },

        resetTimer() {
            clearTimeout(this.debounceTimer);
            this.debounceTimer = setTimeout(() => {
                if (this.remaining > 0) {
                    this.remaining = this.total;
                }
            }, 1000);
        },

        formatTime(seconds) {
            if (seconds < 0) seconds = 0;
            const m = Math.floor(seconds / 60);
            const s = seconds % 60;
            return String(m).padStart(2, '0') + ':' + String(s).padStart(2, '0');
        },

        async logout() {
            clearInterval(this.timerInterval);
            this.remaining = 0;

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

            try {
                await fetch('/logout', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                });
            } catch (e) {
                // ignore
            }

            window.location.href = '/login?expired=1';
        }
    };
});

Alpine.start();

document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.datepicker').forEach(function (el) {
        flatpickr(el, {
            dateFormat: 'Y-m-d',
            altInput: true,
            altFormat: 'd/m/Y',
            locale: 'id',
        });
    });

    // Dispatch activity event for session timer
    ['mousemove', 'keydown', 'click', 'scroll', 'touchstart'].forEach(function (event) {
        document.addEventListener(event, function () {
            window.dispatchEvent(new CustomEvent('activity'));
        }, { passive: true });
    });
});
