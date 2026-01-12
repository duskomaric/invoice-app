@if (session()->has('success') || session()->has('error') || session()->has('warning'))
    <div x-data="{ show: true }"
         x-show="show"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-2"
         x-init="setTimeout(() => show = false, 4000)"
         class="fixed bottom-4 right-4 z-[100] max-w-sm w-full"
         style="display: none;">
        
        @php
            $type = session()->has('error') ? 'error' : (session()->has('warning') ? 'warning' : 'success');
            $message = session('success') ?? session('error') ?? session('warning');
            
            // Detect "delete" or "update" context for styling if generic success
            if ($type === 'success') {
                if (str_contains(strtolower($message), 'delet') || str_contains(strtolower($message), 'destroy')) {
                    $type = 'delete';
                } elseif (str_contains(strtolower($message), 'updat') || str_contains(strtolower($message), 'edit')) {
                    $type = 'update';
                }
            }
            
            $styles = [
                'success' => [
                    'bg' => 'bg-emerald-50 dark:bg-emerald-900/30 border-emerald-200 dark:border-emerald-800',
                    'text' => 'text-emerald-800 dark:text-emerald-200',
                    'icon' => 'bg-emerald-100 dark:bg-emerald-800 text-emerald-600 dark:text-emerald-300',
                    'icon_svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>',
                ],
                'update' => [
                    'bg' => 'bg-blue-50 dark:bg-blue-900/30 border-blue-200 dark:border-blue-800',
                    'text' => 'text-blue-800 dark:text-blue-200',
                    'icon' => 'bg-blue-100 dark:bg-blue-800 text-blue-600 dark:text-blue-300',
                    'icon_svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>',
                ],
                'delete' => [
                    'bg' => 'bg-rose-50 dark:bg-rose-900/30 border-rose-200 dark:border-rose-800',
                    'text' => 'text-rose-800 dark:text-rose-200',
                    'icon' => 'bg-rose-100 dark:bg-rose-800 text-rose-600 dark:text-rose-300',
                    'icon_svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>',
                ],
                'error' => [
                    'bg' => 'bg-rose-50 dark:bg-rose-900/30 border-rose-200 dark:border-rose-800',
                    'text' => 'text-rose-800 dark:text-rose-200',
                    'icon' => 'bg-rose-100 dark:bg-rose-800 text-rose-600 dark:text-rose-300',
                    'icon_svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>',
                ],
                'warning' => [
                    'bg' => 'bg-amber-50 dark:bg-amber-900/30 border-amber-200 dark:border-amber-800',
                    'text' => 'text-amber-800 dark:text-amber-200',
                    'icon' => 'bg-amber-100 dark:bg-amber-800 text-amber-600 dark:text-amber-300',
                    'icon_svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>',
                ]
            ];
            
            $currentStyle = $styles[$type] ?? $styles['success'];
        @endphp

        <div class="flex items-center gap-3 p-4 rounded-xl border shadow-lg backdrop-blur-sm {{ $currentStyle['bg'] }}">
            <div class="w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0 {{ $currentStyle['icon'] }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    {!! $currentStyle['icon_svg'] !!}
                </svg>
            </div>
            
            <div class="flex-1 min-w-0">
                <p class="text-sm font-bold {{ $currentStyle['text'] }}">
                    {{ match($type) {
                        'success' => 'Success',
                        'update' => 'Updated',
                        'delete' => 'Deleted',
                        'error' => 'Error',
                        'warning' => 'Warning',
                        default => 'Notification'
                    } }}
                </p>
                <p class="text-xs opacity-90 truncate {{ $currentStyle['text'] }}">
                    {{ $message }}
                </p>
            </div>

            <button @click="show = false" class="p-1.5 rounded-lg hover:bg-black/5 dark:hover:bg-white/5 transition-colors {{ $currentStyle['text'] }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    </div>
@endif
