<div class="relative" x-data="customSelect" @click.outside="close()" @keydown.escape.window="close()">
    <select {{ $attributes }}
        x-ref="select"
        @pointerdown.prevent="toggle()"
        @mousedown.prevent
        @focusout="close()"
        @keydown="onKeydown($event)">{{ $slot }}</select>

    <ul x-cloak
        x-show="open"
        x-ref="list"
        role="listbox"
        @mousedown.prevent
        class="absolute inset-x-0 top-full z-30 mt-1 max-h-60 overflow-y-auto overscroll-contain rounded-lg border border-gray-200 bg-white py-1 shadow-lg dark:border-gray-700 dark:bg-gray-800"
    >
        <template x-for="(item, index) in items" :key="index">
            <li
                role="option"
                :aria-selected="index === active"
                :aria-disabled="item.disabled"
                :aria-label="item.label"
                class="flex min-h-11 cursor-pointer items-center justify-between gap-3 px-3 py-3 text-sm select-none"
                :class="{
                    'bg-brand-500 text-white font-medium': index === active && !item.disabled,
                    'text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700': index !== active && !item.disabled,
                    'text-gray-400 cursor-not-allowed dark:text-gray-500': item.disabled,
                }"
                @click="choose(index)"
                @mousemove="active = index"
            >
                <span class="truncate" x-text="item.label"></span>
                <i class="fa-solid fa-check text-xs" x-show="index === selected" :class="index === active ? 'text-white' : 'text-brand-500'"></i>
            </li>
        </template>
    </ul>
</div>
