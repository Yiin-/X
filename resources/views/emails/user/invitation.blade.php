@component('mail::message')
# You are invited to {{ $companyName }} on {{ config('app.name') }}!

Hello {{ $name }},

{{ $inviter }} invited you to join {{ $companyName }} on {{ config('app.name') }}!

Current details of your account are:

@component('mail::table', [
    'username' => $username,
    'firstName' => $firstName,
    'lastName' => $lastName,
    'phone' => $phone,
    'email' => $email,
    'jobTitle' => $jobTitle
])
||||
| ------------- | -----------------------:|
| Username      | {{ $username }}         |
| First name    | {{ $firstName ?? '-' }} |
| Last name     | {{ $lastName ?? '-' }}  |
| Phone         | {{ $phone ?? '-' }}     |
| Email         | {{ $email }}            |
| Job title     | {{ $jobTitle ?? '-' }}  |
@endcomponent

You can change them by accepting invitation.

@if ($isPasswordSet)
The password for your account is already set by {{ $inviter }}.
@endif

@component('mail::button', [ 'url' => route('user.accept-invitation', $invitationToken) ])
Accept invitation
@endcomponent

@endcomponent