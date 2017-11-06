<?php

namespace App\Domain\Observers\Documents;

use App\Application\Jobs\GenerateInvoicePdf;
use App\Domain\Model\Documents\Pdf\Pdf;

class PdfObserver
{
    public function saved(Pdf $pdf)
    {

    }
}