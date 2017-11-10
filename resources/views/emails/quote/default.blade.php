@component('mail::message')
# New quote from {{ $company->name }}

### Quote Number: {{ $quote->quote_number }}
### Date: {{ $quote->date->format('d-m-Y') }}
@endcomponent