@component('mail::message')
# Dear {{ $user->username }}

In order to help maintain the security of your {{ config('app.name') }} account, please verify your email address.

@component('mail::button', ['url' => $verificationLink])
Verify my email address
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
