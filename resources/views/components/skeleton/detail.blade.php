<div {{ $attributes->merge(['class' => 'w-full animate-pulse space-y-6']) }}>
    <!-- Header Skeleton -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-gray-100 dark:border-gray-700/60">
        <div class="space-y-2">
            <div class="h-6 bg-gray-200 dark:bg-gray-700 rounded w-64"></div>
            <div class="h-3.5 bg-gray-100 dark:bg-gray-800 rounded w-48"></div>
        </div>
        <div class="flex gap-2">
            <div class="h-9 w-24 bg-gray-200 dark:bg-gray-700 rounded-lg"></div>
            <div class="h-9 w-28 bg-gray-200 dark:bg-gray-700 rounded-lg"></div>
        </div>
    </div>

    <!-- 2 Column Layout Skeleton -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <div class="rounded-2xl border border-gray-200 dark:border-gray-700/60 bg-white dark:bg-gray-800 p-6 space-y-4">
                <div class="h-5 bg-gray-200 dark:bg-gray-700 rounded w-1/3"></div>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 pt-2">
                    @for($i = 0; $i < 6; $i++)
                    <div class="p-3 rounded-xl bg-gray-50 dark:bg-gray-900/50 space-y-2">
                        <div class="h-3 bg-gray-200 dark:bg-gray-700 rounded w-1/2"></div>
                        <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-3/4"></div>
                    </div>
                    @endfor
                </div>
            </div>

            <div class="rounded-2xl border border-gray-200 dark:border-gray-700/60 bg-white dark:bg-gray-800 p-6 space-y-3">
                <div class="h-5 bg-gray-200 dark:bg-gray-700 rounded w-1/4"></div>
                <div class="h-3.5 bg-gray-100 dark:bg-gray-800 rounded w-full"></div>
                <div class="h-3.5 bg-gray-100 dark:bg-gray-800 rounded w-5/6"></div>
                <div class="h-3.5 bg-gray-100 dark:bg-gray-800 rounded w-2/3"></div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <div class="rounded-2xl border border-gray-200 dark:border-gray-700/60 bg-white dark:bg-gray-800 p-6 space-y-4">
                <div class="h-5 bg-gray-200 dark:bg-gray-700 rounded w-1/2"></div>
                <div class="space-y-3">
                    <div class="flex justify-between py-2 border-b border-gray-100 dark:border-gray-700/50">
                        <div class="h-3 bg-gray-200 dark:bg-gray-700 rounded w-1/3"></div>
                        <div class="h-3 bg-gray-200 dark:bg-gray-700 rounded w-1/4"></div>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100 dark:border-gray-700/50">
                        <div class="h-3 bg-gray-200 dark:bg-gray-700 rounded w-1/3"></div>
                        <div class="h-3 bg-gray-200 dark:bg-gray-700 rounded w-1/4"></div>
                    </div>
                    <div class="flex justify-between py-2">
                        <div class="h-3 bg-gray-200 dark:bg-gray-700 rounded w-1/3"></div>
                        <div class="h-3 bg-gray-200 dark:bg-gray-700 rounded w-1/4"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
