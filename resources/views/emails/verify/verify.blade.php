@component('mail::message')
# Email Verification Token

<div>
    Here is your email verification token:
    <ul><b>{{$token}}</b></ul>
    Token is valid for 10 minutes.
</div>

<br>
Thanks,<br>
{{ config('app.name') }}
@endcomponent