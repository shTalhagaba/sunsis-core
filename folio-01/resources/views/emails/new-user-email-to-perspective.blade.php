@component('mail::message')

@component('mail::panel')
>>>> ##New User Created
@endcomponent


A new system user has been created, details:

> **Product Name:** {{ config('app.name') }}<br>
> **Client Name:** {{ \Session::get('configuration')['FOLIO_CLIENT_NAME'] }}<br>
> **Username:** {{ $user->email }}<br>
> **User Type:** {{ $user->user_type }}<br>
> **Total Active Users:** {{ $totalActiveUsers }}<br>
> **Total InActive Users:** {{ $totalInActiveUsers }}<br>


*This email is for Perspective Admin to track how many system users have been created by every Folio client.*

{{ config('app.name') }}
@endcomponent


