@props([
    'activities' => [],
])

<x-app.card>
    <h3 class="text-sm font-bold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
        <svg class="w-4 h-4 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        Activity
    </h3>
    <div class="space-y-3">
        @forelse($activities as $activity)
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700 flex items-center justify-center text-slate-500 dark:text-slate-400 flex-shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <p class="text-xs font-medium text-slate-800 dark:text-white">{{ $activity->description }}</p>
                    <p class="text-[10px] text-slate-500 dark:text-slate-400">{{ $activity->created_at->format('M d, Y H:i') }}</p>
                </div>
            </div>
        @empty
            <div class="flex items-start gap-3 opacity-50">
                <div class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700 flex items-center justify-center text-slate-500 dark:text-slate-400 flex-shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <p class="text-xs font-medium text-slate-800 dark:text-white">Created</p>
                    <p class="text-[10px] text-slate-500 dark:text-slate-400">Just now</p>
                </div>
            </div>
        @endforelse
    </div>
</x-app.card>
