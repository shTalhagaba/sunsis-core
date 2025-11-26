@component('mail::message')

@component('mail::panel')
>>> ##Review Form Sign Email
@endcomponent

Hi {{ $employerUser->firstnames }},

Your comments and signature are required for review session.

> **Learner:** {{ $review->training->student->full_name }}<br>
> **Session Date:** {{ $review->meeting_date->format('d/m/Y') }}<br>

Please click the following button to access the review form in {{ config('app.name') }}.

@component('mail::button', ['url' => $signedUrl])
Review Form
@endcomponent

This link is only valid for 24 hours.

@isset($actionText)
@slot('subcopy')
@lang(
    "If you're having trouble clicking the \":actionText\" button, copy and paste the URL below\n".
    'into your web browser: [:actionURL](:actionURL)',
    [
        'actionText' => $actionText,
        'actionURL' => $actionURL,
    ]
)
@endslot
@endisset

Regards,<br>

{{ App\Facades\AppConfig::get('FOLIO_CLIENT_NAME') }}

@endcomponent