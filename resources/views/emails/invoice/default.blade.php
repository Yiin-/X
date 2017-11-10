@component('mail::message')
# New invoice from {{ $company->name }}

### Invoice Number: {{ $invoice->invoice_number }}
### Date: {{ $invoice->date->format('d-m-Y') }}
@endcomponent