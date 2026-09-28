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
import 'flatpickr/dist/l10n/id.js';

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

Alpine.data('customSelect', () => ({
    open: false,
    items: [],
    active: -1,
    selected: -1,

    get select() {
        return this.$refs.select;
    },

    sync() {
        const select = this.select;
        this.items = Array.from(select.options).map((option) => ({
            value: option.value,
            label: (option.textContent || '').replace(/\s+/g, ' ').trim(),
            disabled: option.disabled,
        }));
        this.selected = select.selectedIndex;
    },

    toggle() {
        if (this.open) {
            this.close();
            return;
        }

        this.sync();
        this.active = this.selected;
        this.open = true;
        this.select.focus();
    },

    close(focusSelect = false) {
        this.open = false;

        if (focusSelect) {
            this.select.focus();
        }
    },

    choose(index) {
        const option = this.select.options[index];

        if (!option || option.disabled) {
            return;
        }

        this.select.value = option.value;
        this.select.dispatchEvent(new Event('change', { bubbles: true }));
        this.open = false;
        this.select.focus();
    },

    highlight(step) {
        if (!this.open) {
            this.sync();
            this.active = this.selected;
            this.open = true;
        }

        const count = this.items.length;
        let index = this.active;

        for (let i = 0; i < count; i++) {
            index = (index + step + count) % count;

            if (!this.items[index].disabled) {
                this.active = index;
                return;
            }
        }
    },

    onKeydown(event) {
        switch (event.key) {
            case 'Enter':
            case ' ':
            case 'Spacebar':
                event.preventDefault();

                if (this.open) {
                    this.choose(this.active);
                } else {
                    this.toggle();
                }

                break;
            case 'ArrowDown':
                event.preventDefault();
                this.highlight(1);
                break;
            case 'ArrowUp':
                event.preventDefault();
                this.highlight(-1);
                break;
            case 'Escape':
                if (this.open) {
                    event.preventDefault();
                    this.close();
                }

                break;
            case 'Tab':
                this.open = false;
                break;
            default:
                break;
        }
    },
}));

Alpine.start();

document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.datepicker').forEach(function (el) {
        const config = {
            dateFormat: 'Y-m-d',
            altInput: true,
            altFormat: 'd/m/Y',
            locale: 'id',
        };
        const minDate = el.getAttribute('data-min-date');
        if (minDate) {
            config.minDate = minDate;
        }
        flatpickr(el, config);
    });

    // Dispatch activity event for session timer
    ['mousemove', 'keydown', 'click', 'scroll', 'touchstart'].forEach(function (event) {
        document.addEventListener(event, function () {
            window.dispatchEvent(new CustomEvent('activity'));
        }, { passive: true });
    });
});
