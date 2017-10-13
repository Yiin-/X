@component('mail::message')
# Welcome to Overseer.io

{{ $user->full_name }}, we're very happy to have you with us!

Just one last thing, we need to confirm your email address. To do that, simply
click the button below.

@component('mail::button', ['url' => route('user.confirmation', $user->confirmation_token)])
Confirm Email
@endcomponent

@endcomponent