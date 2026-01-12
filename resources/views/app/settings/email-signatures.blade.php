<x-app.settings-layout :company="$company" active="email">
    <x-app.card>
        <div class="flex items-center justify-between mb-8">
            <x-app.section-header title="Email Signatures" subtitle="Manage your professional email endings" icon="pencil-square" variant="secondary" />
            <x-app.button variant="primary" size="sm">
                <x-heroicon-o-plus class="w-4 h-4 mr-1" />
                Add Signature
            </x-app.button>
        </div>

        <div class="space-y-4">
            @forelse($signatures as $signature)
                <div class="p-6 rounded-3xl bg-slate-50 dark:bg-slate-900/50 border border-slate-100 dark:border-slate-800 transition-all hover:shadow-md group">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-white dark:bg-slate-800 shadow-sm flex items-center justify-center text-blue-500">
                                <x-heroicon-o-pencil class="w-6 h-6" />
                            </div>
                            <div>
                                <h4 class="text-sm font-black text-slate-800 dark:text-slate-200 uppercase tracking-wider">{{ $signature->name }}</h4>
                                <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1">Status: {{ $signature->is_active ? 'Active' : 'Inactive' }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                            <x-app.button variant="secondary" size="xs">Edit</x-app.button>
                            <x-app.button variant="danger" size="xs">Delete</x-app.button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-12">
                    <div class="w-16 h-16 rounded-3xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-300 mx-auto mb-4">
                        <x-heroicon-o-pencil-square class="w-8 h-8" />
                    </div>
                    <p class="text-xs font-bold text-slate-500 italic">No custom signatures found.</p>
                </div>
            @endforelse
        </div>
    </x-app.card>
</x-app.settings-layout>
