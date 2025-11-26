@component('mail::message')

@component('mail::panel')
>>>> ##Password has been reset
@endcomponent

Hi {{ $user->firstnames }},

Your password has successfully been reset for your {{ config('app.name') }} account.

If you did not request a password reset, contact your administrator immediately or reach out to your internal support for assistance.

Regards,<br>

{{ App\Facades\AppConfig::get('FOLIO_CLIENT_NAME') }}

<br>
{{ config('app.name') }}
@endcomponent