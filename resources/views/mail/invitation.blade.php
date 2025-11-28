<x-mail::message>
# Invitation to Join {{ config('app.name') }}
You are invited to join {{ config('app.name') }}. Please click the button below to accept the invitation and create your account.
<x-mail::button :url="$url">
Accept Invitation
</x-mail::button>
We look forward to having you on board!
<x-mail::panel>
If you have any questions or need assistance, please contact us.
</x-mail::panel>
</x-mail::message>
