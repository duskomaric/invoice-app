@extends('layouts.app')

@section('title', 'Bank Accounts - ' . $company->name)
@section('page-title', 'Bank Accounts')
@section('page-subtitle', 'Manage company payout accounts')

@section('content')
<x-app.settings-layout :company="$company" active="bank">
    <div x-data="{ 
        showAccountModal: false, 
        editingAccount: { bank_name: '', account_number: '', currency: '', swift: '', iban: '' },
        openModal(account = null) {
            if (account) {
                this.editingAccount = { ...account };
            } else {
                this.editingAccount = { bank_name: '', account_number: '', currency: '', swift: '', iban: '' };
            }
            this.showAccountModal = true;
        }
    }">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($bankAccounts as $account)
                <x-app.card class="relative overflow-hidden group">
                    <div class="absolute top-0 right-0 w-24 h-24 -mr-12 -mt-12 bg-emerald-500/5 rounded-full group-hover:bg-emerald-500/10 transition-colors"></div>
                    
                    <div class="flex items-start justify-between mb-4">
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-500/10 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                            <x-heroicon-o-credit-card class="w-5 h-5" />
                        </div>
                        <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                            <x-app.button @click="openModal({{ $account->toJson() }})" variant="ghost" size="icon-xs" title="Edit">
                                <x-heroicon-o-pencil-square class="w-4 h-4" />
                            </x-app.button>
                            <form action="{{ route('app.settings.bank-accounts.destroy', [$company, $account]) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                @csrf
                                @method('DELETE')
                                <x-app.button type="submit" variant="ghost" size="icon-xs" class="text-rose-500 hover:text-rose-600" title="Delete">
                                    <x-heroicon-o-trash class="w-4 h-4" />
                                </x-app.button>
                            </form>
                        </div>
                    </div>

                    <h3 class="text-sm font-black text-slate-900 dark:text-white uppercase tracking-tight">{{ $account->bank_name }}</h3>
                    <div class="text-xs font-mono text-slate-500 mt-1 mb-4 select-all">{{ $account->account_number }}</div>
                    
                    <div class="flex items-center justify-between pt-4 border-t border-slate-50 dark:border-slate-800">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Currency</span>
                        <x-app.status-badge :status="$account->currency" variant="emerald" size="sm" />
                    </div>
                </x-app.card>
            @empty
                <div class="lg:col-span-3 text-center py-12 bg-slate-50 dark:bg-slate-900/50 rounded-3xl border-2 border-dashed border-slate-200 dark:border-slate-800">
                    <x-heroicon-o-credit-card class="w-12 h-12 mx-auto mb-4 text-slate-300" />
                    <p class="text-slate-500 font-medium">No bank accounts linked yet</p>
                    <x-app.button @click="openModal()" variant="primary" size="sm" class="mt-4">
                        <x-heroicon-o-plus class="w-4 h-4" />
                        Connect Bank Account
                    </x-app.button>
                </div>
            @endforelse

            @if($bankAccounts->count() > 0)
            <div @click="openModal()" class="flex items-center justify-center min-h-[180px] rounded-xl border-2 border-dashed border-slate-200 dark:border-slate-700 hover:border-violet-400 dark:hover:border-violet-600 bg-slate-50/50 dark:bg-slate-800/30 transition-all cursor-pointer group">
                <div class="flex flex-col items-center py-8">
                    <div class="w-12 h-12 rounded-xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-400 group-hover:bg-violet-100 dark:group-hover:bg-violet-900/30 group-hover:text-violet-600 dark:group-hover:text-violet-400 transition-colors mb-3">
                        <x-heroicon-o-plus class="w-6 h-6" />
                    </div>
                    <span class="text-xs font-bold text-slate-400 group-hover:text-violet-600 dark:group-hover:text-violet-400 transition-colors uppercase tracking-wider">Add Account</span>
                </div>
            </div>
            @endif
        </div>

        {{-- Add/Edit Modal --}}
        <div x-show="showAccountModal" class="fixed inset-0 z-50 overflow-y-auto" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             x-cloak>
            <div class="flex items-center justify-center min-h-screen px-4 py-6 text-center">
                <div class="fixed inset-0 transition-opacity bg-slate-900/60 backdrop-blur-sm" @click="showAccountModal = false"></div>

                <div class="inline-block w-full max-w-lg p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white dark:bg-slate-900 rounded-3xl shadow-2xl border border-slate-200 dark:border-slate-800 relative z-10">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-black text-slate-900 dark:text-white uppercase tracking-tight" x-text="editingAccount.id ? 'Edit Bank Account' : 'Add Bank Account'"></h3>
                        <button @click="showAccountModal = false" class="text-slate-400 hover:text-slate-500 transition-colors">
                            <x-heroicon-o-x-mark class="w-6 h-6" />
                        </button>
                    </div>

                    <form :action="editingAccount.id ? '{{ route('app.settings.bank-accounts.update', [$company, ':id']) }}'.replace(':id', editingAccount.id) : '{{ route('app.settings.bank-accounts.store', $company) }}'" method="POST" class="space-y-4">
                        @csrf
                        <template x-if="editingAccount.id">
                            <input type="hidden" name="_method" value="PUT">
                        </template>

                        <x-app.input label="Bank Name" name="bank_name" x-model="editingAccount.bank_name" required placeholder="e.g. Sparkasse Bank" />
                        <x-app.input label="Account Number" name="account_number" x-model="editingAccount.account_number" required placeholder="e.g. 1610000012345678" />
                        
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 uppercase tracking-wider">Currency</label>
                            <select name="currency" x-model="editingAccount.currency" class="w-full h-11 px-4 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-sm text-slate-800 dark:text-white focus:outline-none focus:ring-4 focus:ring-violet-500/10 focus:border-violet-400 transition-all shadow-sm">
                                <option value="">Select Currency</option>
                                @foreach($currencies as $c)
                                    <option value="{{ $c->code }}">{{ $c->code }} - {{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <x-app.input label="SWIFT / BIC" name="swift" x-model="editingAccount.swift" placeholder="Optional" />
                            <x-app.input label="IBAN" name="iban" x-model="editingAccount.iban" placeholder="Optional" />
                        </div>

                        <div class="pt-4 flex items-center gap-3">
                            <x-app.button @click="showAccountModal = false" type="button" variant="secondary" class="flex-1">Cancel</x-app.button>
                            <x-app.button type="submit" variant="primary" class="flex-1" x-text="editingAccount.id ? 'Update Account' : 'Save Account'"></x-app.button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app.settings-layout>
@endsection
