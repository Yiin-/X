@component('mail::message')
# New quote from {{ $company->name }}

### Quote Number: {{ $quote->bill->number }}
### Date: {{ $quote->bill->date->format('d-m-Y') }}
@endcomponent