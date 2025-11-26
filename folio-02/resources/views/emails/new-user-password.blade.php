@component('mail::message')

Hi {{ $user->firstnames }},

Following is your password for **Folio** account:

> {{ $password }}<br>

*you must have received welcome email containing your username. If you havent't received welcome email, please contact Folio system administrator.*


@component('mail::button', ['url' => App\Facades\AppConfig::get('FOLIO_CLIENT_URL').'/login'])
Login to Folio
@endcomponent


Regards,<br>

{{ App\Facades\AppConfig::get('FOLIO_CLIENT_NAME') }}

<br>
{{ config('app.name') }}
@endcomponent


