@component('mail::message')

@slot('header')
@component('mail::header', ['url' => '#', 'color' => 'green'])
Dear {{ $data['email'] }}, the password for your account has been changed.<br>
@endcomponent
@endslot

If you did not change your password please contact an adminstrator.

Thanks,<br>
{{ config('app.name') }}
@endcomponent