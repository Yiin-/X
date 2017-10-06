<?php

namespace App\Domain\Observers\Documents;

use App\Application\Jobs\GenerateInvoicePdf;
use App\Domain\Model\Documents\Invoice\Invoice;

class InvoiceObserver
{
    public function saved(Invoice $invoice)
    {
        // Invoice was not changed in any way
        if (!$invoice->isDirty()) {
            return;
        }

        GenerateInvoicePdf::dispatch($invoice);
    }
}