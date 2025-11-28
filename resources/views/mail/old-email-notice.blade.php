<x-mail::message>
# Email Change Notification

Hello,

We received a request to change the email for your account to: **{{ $newEmail }}**.

If you did NOT request this change, you can **block it immediately**:

<x-mail::button :url="$blockUrl">
Block Email Change
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
