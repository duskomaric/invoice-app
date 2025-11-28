<x-mail::message>
# Verify Your New Email

Hello,

You requested to change your email address. Please click the button below to verify your new email:

<x-mail::button :url="$verificationUrl">
Verify Email
</x-mail::button>

If you did not request this change, please ignore this email.

<x-mail::panel>
The verification link will expire in 60 minutes.
</x-mail::panel>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
