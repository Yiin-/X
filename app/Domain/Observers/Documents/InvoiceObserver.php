<?php

namespace App\Domain\Observers\Documents;

use App\Application\Jobs\GenerateInvoicePdf;
use App\Domain\Model\Documents\Invoice\Invoice;
use App\Domain\Observers\Documents\Traits\DisablesChildren;

class InvoiceObserver
{
    use DisablesChildren;

    protected $children = [
        'payments'
    ];

    public function saved(Invoice $invoice)
    {
        // Invoice was not changed in any way
        if (!$invoice->isDirty()) {
            return;
        }
        $invoice->loadRelationships();

        \Log::debug('Generating pdf for invoice #' . $invoice->invoice_number);
        GenerateInvoicePdf::dispatch($invoice);
    }
}