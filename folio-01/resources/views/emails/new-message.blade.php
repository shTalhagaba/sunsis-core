@component('mail::message')

@component('mail::panel')
    >>>> ##New Message in Folio
@endcomponent

Hi {{ $recipient->firstnames }},

You have a new message in Folio. Please login and read your message at your earliest convenience.

@component('mail::button', ['url' => \Session::get('configuration')['FOLIO_CLIENT_URL'].'/login'])
    Login to Folio
@endcomponent

Regards,<br>

{{ \Session::get('configuration')['FOLIO_CLIENT_NAME'] }}

<br>
{{ config('app.name') }}
@endcomponent


