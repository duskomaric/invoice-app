@props([
    'hoverable' => true,
    'striped' => false,
])

<div class="overflow-hidden rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-sm">
    <div class="overflow-x-auto">
        <table {{ $attributes->merge(['class' => 'w-full']) }}>
            @isset($head)
            <thead>
                <tr class="border-b border-slate-200 dark:border-slate-700 bg-slate-100/50 dark:bg-slate-900/50">
                    {{ $head }}
                </tr>
            </thead>
            @endisset
            <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50 bg-white dark:bg-slate-800">
                {{ $slot }}
            </tbody>
        </table>
    </div>
    @isset($pagination)
    <div class="px-4 py-3 border-t border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50">
        {{ $pagination }}
    </div>
    @endisset
</div>
