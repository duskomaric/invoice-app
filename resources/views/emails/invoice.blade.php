<x-mail::message>
{{ $body }}

<x-mail::button :url="$clickUrl">
Download Invoice
</x-mail::button>

<img src="{{ $pixelUrl }}" width="1" height="1" alt="" />
</x-mail::message>
