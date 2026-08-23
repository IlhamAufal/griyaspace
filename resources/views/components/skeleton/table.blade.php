@props([
    'rows' => 5,
    'cols' => 4,
    'showHeader' => true,
])

<div {{ $attributes->merge(['class' => 'w-full animate-pulse overflow-hidden']) }}>
    @if($showHeader)
    <div class="flex items-center justify-between gap-4 pb-4 mb-4 border-b border-gray-100 dark:border-gray-700/60">
        <div class="h-5 bg-gray-200 dark:bg-gray-700 rounded-md w-1/4"></div>
        <div class="h-8 bg-gray-200 dark:bg-gray-700 rounded-lg w-28"></div>
    </div>
    @endif

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-gray-100 dark:border-gray-700/60">
                    @for($c = 0; $c < $cols; $c++)
                    <th class="py-3 px-4 text-left">
                        <div class="h-3.5 bg-gray-200 dark:bg-gray-700 rounded-md w-{{ $c === 0 ? '3/4' : ($c === $cols - 1 ? '1/2' : '2/3') }}"></div>
                    </th>
                    @endfor
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700/40">
                @for($r = 0; $r < $rows; $r++)
                <tr>
                    @for($c = 0; $c < $cols; $c++)
                    <td class="py-3.5 px-4">
                        @if($c === 0)
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-gray-200 dark:bg-gray-700 shrink-0"></div>
                            <div class="space-y-1.5 flex-1">
                                <div class="h-3.5 bg-gray-200 dark:bg-gray-700 rounded w-4/5"></div>
                                <div class="h-2.5 bg-gray-100 dark:bg-gray-800 rounded w-1/2"></div>
                            </div>
                        </div>
                        @elseif($c === $cols - 1)
                        <div class="flex items-center justify-end gap-2">
                            <div class="h-7 w-16 bg-gray-200 dark:bg-gray-700 rounded-lg"></div>
                        </div>
                        @elseif($c === 1)
                        <div class="h-3.5 bg-gray-200 dark:bg-gray-700 rounded w-3/5"></div>
                        @else
                        <div class="h-6 w-20 bg-gray-200 dark:bg-gray-700 rounded-full"></div>
                        @endif
                    </td>
                    @endfor
                </tr>
                @endfor
            </tbody>
        </table>
    </div>
</div>
