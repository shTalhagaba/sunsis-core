@component('mail::message')

@component('mail::panel')
    >>>> ##{{ $config['FOLIO_CLIENT_NAME'] }} Folio Message
@endcomponent

Hi {{ $student->firstnames }},

We have noticed you have not logged into your Folio account during the last 28 days.
Can you please login to review your progress and respond to any feedback or messages left by your Assessor.


@component('mail::button', ['url' => $config['FOLIO_CLIENT_URL'].'/login'])
    Login to Folio
@endcomponent

Regards,<br>

{{ $config['FOLIO_CLIENT_NAME'] }}


@endcomponent


