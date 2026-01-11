@props([
    'striped' => false,
    'hoverable' => true,
])

<div class="overflow-hidden border border-neutral-200/60 rounded-lg">
    <div class="overflow-x-auto">
        <table {{ $attributes->merge(['class' => 'min-w-full divide-y divide-neutral-200']) }}>
            @if(isset($head))
            <thead class="bg-neutral-50">
                {{ $head }}
            </thead>
            @endif
            <tbody class="divide-y divide-neutral-200 bg-white">
                {{ $slot }}
            </tbody>
            @if(isset($foot))
            <tfoot class="bg-neutral-50">
                {{ $foot }}
            </tfoot>
            @endif
        </table>
    </div>
</div>
