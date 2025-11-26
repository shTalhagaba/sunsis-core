@component('mail::message')

@component('mail::panel')
>>>> ##New User Created
@endcomponent


A new system user has been created, details:

> **Product Name:** {{ config('app.name') }}<br>
> **Client Name:** {{ App\Facades\AppConfig::get('FOLIO_CLIENT_NAME') }}<br>
> **Username:** {{ $user->username }}<br>
> **User Type:** {{ $user->systemUsertype->description }}<br>
> **Total Active Users:** {{ $totalActiveUsers }}<br>
> **Total InActive Users:** {{ $totalInActiveUsers }}<br>


*This email is for Perspective Admin to track how many system users have been created by every Folio client.*

{{ config('app.name') }}
@endcomponent


