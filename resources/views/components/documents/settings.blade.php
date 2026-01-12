@props([
    'previewNumber' => null,
    'currencies' => [],
])

<x-app.card>
    <x-app.section-header 
        title="Document Settings" 
        subtitle="Configuration & defaults" 
        icon="cog-6-tooth" 
        variant="primary" 
    />
    
    <div class="space-y-4">
        {{-- Next Number / Document Number --}}
        <div class="p-4 rounded-xl bg-violet-50 dark:bg-violet-900/20 border border-violet-100 dark:border-violet-800/50 flex flex-col justify-center text-center">
            <span class="text-xs font-bold text-violet-600 dark:text-violet-400 uppercase tracking-wider mb-1">Document Number</span>
            <span class="text-lg font-mono font-bold text-violet-900 dark:text-violet-100">{{ $previewNumber ?? 'INV-????-????' }}</span>
        </div>

        <x-app.input label="Date" type="date" name="date" x-model="date" />
        <x-app.input label="Due Date" type="date" name="due_date" x-model="dueDate" />
        
        <div>
            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Status</label>
            <select name="status" class="w-full h-10 px-3 rounded-lg bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 text-sm text-slate-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-violet-500/20 focus:border-violet-400 dark:focus:border-violet-500 transition-all shadow-sm">
                <option value="draft">Draft</option>
                <option value="pending">Pending</option>
                <option value="paid">Paid</option>
            </select>
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Currency</label>
            <select name="currency" x-model="currency" @change="updateCurrency($event.target.value)" class="w-full h-10 px-3 rounded-lg bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 text-sm text-slate-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-violet-500/20 focus:border-violet-400 dark:focus:border-violet-500 transition-all shadow-sm">
                @foreach($currencies as $curr)
                    <option value="{{ $curr->code }}">{{ $curr->code }} - {{ $curr->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Language</label>
            <select name="language" x-model="language" class="w-full h-10 px-3 rounded-lg bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 text-sm text-slate-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-violet-500/20 focus:border-violet-400 dark:focus:border-violet-500 transition-all shadow-sm">
                <option value="en">English (US)</option>
                <option value="ba">Bosnian (BA)</option>
                <option value="de">German (DE)</option>
            </select>
        </div>
    </div>
</x-app.card>
