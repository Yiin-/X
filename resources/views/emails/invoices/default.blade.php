@component('mail::message')
# You've got new invoice from {{ $invoice->user->full_name }}
<br>
{{ config('app.name') }}
@endcomponent
