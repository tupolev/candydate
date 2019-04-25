<p>
    @if($success === true)
        Thank you for verifying your {{ env('APP_NAME') }} account, {{ $username }}. You can log in now using the app.
    @else
        Your {{ env('APP_NAME') }} account could not be verified, {{ $username }}. Please, contact our support.
    @endif
</p>
<br/><br/><br/>
{{ env('APP_NAME') }}
