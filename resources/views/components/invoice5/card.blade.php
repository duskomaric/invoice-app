@props([
    'class' => '',
    'padding' => 'p-3',
    'hover' => false,
    'gradient' => false,
])

<div {{ $attributes->merge([
    'class' => 'rounded-xl border transition-all duration-300 ' . 
        ($gradient ? 'bg-gradient-to-br from-white to-slate-50 dark:from-slate-800 dark:to-slate-800/80' : 'bg-white dark:bg-slate-800') .
        ' border-slate-200 dark:border-slate-700 shadow-sm shadow-slate-900/5 dark:shadow-black/10 ' .
        ($hover ? 'hover:shadow-lg hover:scale-[1.01] hover:border-violet-300 dark:hover:border-violet-500/50 cursor-pointer' : '') .
        ' ' . $padding . ' ' . $class
]) }}>
    {{ $slot }}
</div>
