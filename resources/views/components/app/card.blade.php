@props([
    'class' => '',
    'padding' => 'p-4',
    'hover' => false,
    'gradient' => false,
])

<div {{ $attributes->merge([
    'class' => 'rounded-xl border transition-all duration-200 ' . 
        ($gradient ? 'bg-white dark:bg-slate-800' : 'bg-white dark:bg-slate-800') .
        ' border-slate-200/80 dark:border-slate-700 shadow-sm ' .
        ($hover ? 'hover:shadow-md hover:border-violet-300 dark:hover:border-violet-500/50 cursor-pointer' : '') .
        ' ' . $padding . ' ' . $class
]) }}>
    {{ $slot }}
</div>
