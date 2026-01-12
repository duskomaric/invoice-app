@props([
    'text' => 'Delete this document',
    'subtext' => 'Once deleted, this cannot be undone',
    'buttonText' => 'Delete',
    'action' => null,
])

<x-app.card class="border-rose-200 dark:border-rose-900/50">
    <x-app.section-header 
        title="Danger Zone" 
        subtitle="Irreversible actions" 
        icon="exclamation-triangle" 
        variant="danger" 
    />
    <div class="flex items-center justify-between p-3 rounded-lg bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800/60">
        <div>
            <p class="text-sm font-medium text-rose-800 dark:text-rose-200">{{ $text }}</p>
            <p class="text-xs text-rose-600/70 dark:text-rose-300/70">{{ $subtext }}</p>
        </div>
        <x-app.button variant="danger" size="sm" @click="{{ $action ?? '' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            {{ $buttonText }}
        </x-app.button>
    </div>
</x-app.card>
