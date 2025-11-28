<x-filament-panels::page.simple>
    @if (filament()->hasLogin())
        <x-slot name="subheading">
            or
            {{ $this->loginAction }}
        </x-slot>
    @endif

    <form wire:submit="register" class="space-y-6">
        {{ $this->form }}

        <div class="flex items-center justify-end gap-2" style="margin-top: 20px;">
            @foreach ($this->getFormActions() as $action)
                {{ $action }}
            @endforeach
        </div>
    </form>
</x-filament-panels::page.simple>
