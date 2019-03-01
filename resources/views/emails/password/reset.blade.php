@component('mail::message')

@slot('header')
@component('mail::header', ['url' => '#', 'color' => 'green'])
We have just received a password reset request for {{ $data['email'] }}.<br>
@endcomponent
@endslot

@component('mail::button', ['url' => $data['url']])
Please click here to reset your password
@endcomponent

If the above link does not work, please, copy and paste the following link into your browser:<br>
{{ $data['url'] }}

Thanks,<br>
{{ config('app.name') }}
@endcomponent