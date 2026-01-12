@props([
    'statuses' => [],
    'colors' => [],
    'paramName' => 'status',
    'allLabel' => 'All Statuses',
])

<a href="{{ request()->fullUrlWithQuery([$paramName => '']) }}" class="w-full px-3 py-2 text-left text-xs hover:bg-slate-50 dark:hover:bg-slate-700/50 flex items-center gap-2 {{ !request($paramName) ? 'text-violet-600 dark:text-violet-400 bg-violet-50 dark:bg-violet-900/20' : 'text-slate-600 dark:text-slate-400' }}">
    <span class="w-4 h-4 rounded border flex items-center justify-center {{ !request($paramName) ? 'border-violet-500 bg-violet-500' : 'border-slate-300 dark:border-slate-600' }}">
        @if(!request($paramName))
            <x-heroicon-o-check class="w-3 h-3 text-white" />
        @endif
    </span>
    {{ $allLabel }}
</a>
@foreach($statuses as $status)
    <a href="{{ request()->fullUrlWithQuery([$paramName => $status]) }}" class="w-full px-3 py-2 text-left text-xs hover:bg-slate-50 dark:hover:bg-slate-700/50 flex items-center gap-2 {{ request($paramName) == $status ? 'text-violet-600 dark:text-violet-400 bg-violet-50 dark:bg-violet-900/20' : 'text-slate-600 dark:text-slate-400' }}">
        <span class="w-4 h-4 rounded border flex items-center justify-center {{ request($paramName) == $status ? 'border-violet-500 bg-violet-500' : 'border-slate-300 dark:border-slate-600' }}">
            @if(request($paramName) == $status)
                <x-heroicon-o-check class="w-3 h-3 text-white" />
            @endif
        </span>
        <span class="w-2 h-2 rounded-full {{ $colors[$status] ?? 'bg-slate-500' }}"></span>
        {{ ucfirst($status) }}
    </a>
@endforeach
