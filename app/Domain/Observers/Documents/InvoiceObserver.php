<?php

namespace App\Domain\Observers\Documents;

use App\Application\Jobs\GenerateInvoicePdf;
use App\Domain\Model\Documents\Invoice\Invoice;

class InvoiceObserver
{
    public function saved(Invoice $invoice)
    {
        \Log::debug('saved. ' . ($invoice->isDirty() ? 'dirty' : 'wtf'));
        // Invoice was not changed in any way
        if (!$invoice->isDirty()) {
            return;
        }

        \Log::debug('dispatching GenerateInvoicePdf for invoice ' . $invoice->bill->number);
        GenerateInvoicePdf::dispatch($invoice);
    }
}