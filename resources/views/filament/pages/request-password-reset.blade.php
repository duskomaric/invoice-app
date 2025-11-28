<x-filament-panels::page.simple>
    <form wire:submit.prevent="request">
        {{ $this->form }}

{{--        <div class="mt-4">--}}
{{--            <button type="submit" class="fi-button fi-button-primary">--}}
{{--                Send Reset Link--}}
{{--            </button>--}}
{{--        </div>--}}

        <div class="flex items-center justify-end gap-2" style="margin-top: 20px;">
            @foreach ($this->getFormActions() as $action)
                {{ $action }}
            @endforeach
        </div>
    </form>
</x-filament-panels::page.simple>
