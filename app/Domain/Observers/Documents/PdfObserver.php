<?php

namespace App\Domain\Observers\Documents;

use App\Application\Jobs\GenerateInvoicePdf;

class PdfObserver
{
    public function saved(Pdf $pdf)
    {

    }
}