@props([
    'showRoute' => null,
    'editRoute' => null,
    'deleteRoute' => null,
    'size' => 'icon-xs',
])

<div class="flex items-center justify-end gap-1">
    @if($showRoute)
        <x-app.button :href="$showRoute" variant="ghost" :size="$size" title="View">
            <x-heroicon-o-eye class="w-4 h-4" />
        </x-app.button>
    @endif
    @if($editRoute)
        <x-app.button :href="$editRoute" variant="ghost" :size="$size" title="Edit">
            <x-heroicon-o-pencil-square class="w-4 h-4" />
        </x-app.button>
    @endif
    @if($deleteRoute)
        <form action="{{ $deleteRoute }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?')">
            @csrf
            @method('DELETE')
            <x-app.button type="submit" variant="ghost" :size="$size" class="text-rose-500 hover:text-rose-600" title="Delete">
                <x-heroicon-o-trash class="w-4 h-4" />
            </x-app.button>
        </form>
    @endif
</div>
