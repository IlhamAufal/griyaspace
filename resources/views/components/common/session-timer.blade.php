<div x-data="sessionTimer" x-init="init()" x-show="remaining <= total"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     @activity.window="resetTimer()"
     class="mt-auto border-t border-gray-200 dark:border-gray-800 py-4 px-1"
     :class="{
         'flex flex-col items-center': !$store.sidebar.isExpanded && !$store.sidebar.isHovered && !$store.sidebar.isMobileOpen,
         'px-0': $store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen
     }">

    <!-- Expanded State -->
    <div x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen"
         class="w-full px-3">
        <!-- Timer Display -->
        <div class="flex items-center gap-2.5 mb-2">
            <span class="flex items-center justify-center w-7 h-7 rounded-full flex-shrink-0"
                  :class="{
                      'bg-blue-100 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400': !isWarning && !isDanger,
                      'bg-amber-100 dark:bg-amber-900/40 text-amber-600 dark:text-amber-400': isWarning && !isDanger,
                      'bg-red-100 dark:bg-red-900/40 text-red-600 dark:text-red-400': isDanger
                  }">
                <i class="fa-solid fa-clock text-sm"></i>
            </span>
            <div class="flex-1 min-w-0">
                <div class="text-[11px] text-gray-400 dark:text-gray-500 leading-none mb-0.5">Sisa Waktu Aktif</div>
                <div class="text-sm font-mono font-semibold tabular-nums leading-none"
                     :class="{
                         'text-gray-700 dark:text-gray-200': !isWarning && !isDanger,
                         'text-amber-600 dark:text-amber-400': isWarning && !isDanger,
                         'text-red-600 dark:text-red-400 animate-pulse': isDanger
                     }"
                     x-text="formatTime(remaining)">
                </div>
            </div>
            <button @click="expanded = !expanded"
                    class="flex items-center justify-center w-6 h-6 rounded text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                <i class="fa-solid fa-chevron-up text-xs transition-transform duration-200" :class="{ 'rotate-180': expanded }"></i>
            </button>
        </div>

        <!-- Progress Bar -->
        <div class="w-full h-1 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden mb-2">
            <div class="h-full rounded-full transition-all duration-1000 ease-linear"
                 :class="{
                     'bg-blue-500': !isWarning && !isDanger,
                     'bg-amber-500': isWarning && !isDanger,
                     'bg-red-500': isDanger
                 }"
                 :style="`width: ${(remaining / total) * 100}%`">
            </div>
        </div>

        <!-- Expanded Detail -->
        <div x-show="expanded" x-cloak
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-1"
             x-transition:enter-end="opacity-100 translate-y-0">
            <div class="text-[11px] text-gray-400 dark:text-gray-500 mb-2">
                <span x-show="!isDanger">Timer reset saat ada aktivitas</span>
                <span x-show="isDanger" class="text-red-500 dark:text-red-400 font-medium">Session berakhir segera!</span>
            </div>
            <button @click="logout()"
                    class="w-full px-3 py-1.5 text-xs font-medium rounded-lg transition-colors
                           bg-gray-100 hover:bg-red-50 dark:bg-gray-700 dark:hover:bg-red-900/30
                           text-gray-600 hover:text-red-600 dark:text-gray-400 dark:hover:text-red-400
                           border border-gray-200 hover:border-red-200 dark:border-gray-600 dark:hover:border-red-800">
                <i class="fa-solid fa-right-from-bracket mr-1"></i>
                Logout
            </button>
        </div>
    </div>

    <!-- Collapsed State (icon only) -->
    <div x-show="!$store.sidebar.isExpanded && !$store.sidebar.isHovered && !$store.sidebar.isMobileOpen"
         class="flex flex-col items-center gap-1">
        <span class="flex items-center justify-center w-8 h-8 rounded-full"
              :class="{
                  'bg-blue-100 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400': !isWarning && !isDanger,
                  'bg-amber-100 dark:bg-amber-900/40 text-amber-600 dark:text-amber-400': isWarning && !isDanger,
                  'bg-red-100 dark:bg-red-900/40 text-red-600 dark:text-red-400 animate-pulse': isDanger
              }">
            <i class="fa-solid fa-clock text-sm"></i>
        </span>
        <span class="text-[10px] font-mono font-semibold tabular-nums"
              :class="{
                  'text-gray-600 dark:text-gray-300': !isWarning && !isDanger,
                  'text-amber-600 dark:text-amber-400': isWarning && !isDanger,
                  'text-red-600 dark:text-red-400': isDanger
              }"
              x-text="formatTime(remaining)">
        </span>
    </div>
</div>
