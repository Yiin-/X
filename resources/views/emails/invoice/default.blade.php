@component('mail::message')
# New invoice from {{ $company->name }}

### Invoice Number: {{ $invoice->bill->number }}
### Date: {{ $invoice->bill->date->format('d-m-Y') }}
@endcomponent