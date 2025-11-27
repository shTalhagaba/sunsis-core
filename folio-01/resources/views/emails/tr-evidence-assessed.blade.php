@component('mail::message')

@component('mail::panel')
>>>> ##Evidence Assessed
@endcomponent

Hi {{ $student->firstnames }},

Your **{{ config('app.name') }}** assessor has reviewed your latest work and has left helpful feedback in your Folio.

**Evidence Details:**<br>
Name: {{ $evidence->evidence_name  }}<br>
Created at: {{ \Carbon\Carbon::parse($evidence->created_at)->format('d/m/Y H:i:s') }}<br>
Updated at: {{ \Carbon\Carbon::parse($evidence->updated_at)->format('d/m/Y H:i:s')  }}


Please login and respond to the feedback at your earliest convenience.

@component('mail::button', ['url' => \Session::get('configuration')['FOLIO_CLIENT_URL'].'/login'])
Login to Folio
@endcomponent

Regards,<br>

{{ \Session::get('configuration')['FOLIO_CLIENT_NAME'] }}

<br>
{{ config('app.name') }}
@endcomponent


