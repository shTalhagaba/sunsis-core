@component('mail::message')

@component('mail::panel')
>>>> ##Welcome Email
@endcomponent

Hi {{ $user->firstnames }},

Your **{{ config('app.name') }}** account has been created, your username is as follows:

> **Username:** {{ $user->email }}<br>

*you will receive another email containing your password. If you don't receive password email, please contact Folio system administrator.*

@component('mail::button', ['url' => \Session::get('configuration')['FOLIO_CLIENT_URL'].'/login'])
Login to Folio
@endcomponent

Regards,<br>

{{ \Session::get('configuration')['FOLIO_CLIENT_NAME'] }}

<br>
{{ config('app.name') }}
@endcomponent


