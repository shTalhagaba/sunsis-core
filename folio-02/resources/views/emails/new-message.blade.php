@component('mail::message')

@component('mail::panel')
    >>>> ##New Message in Folio
@endcomponent

Hi {{ $recipient->firstnames }},

You have a new message in Folio. Please login and read your message at your earliest convenience.

@component('mail::button', ['url' => App\Facades\AppConfig::get('FOLIO_CLIENT_URL').'/login'])
    Login to Folio
@endcomponent

Regards,<br>

{{ App\Facades\AppConfig::get('FOLIO_CLIENT_NAME') }}

<br>
{{ config('app.name') }}
@endcomponent


