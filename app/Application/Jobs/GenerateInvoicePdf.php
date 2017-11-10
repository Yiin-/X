<?php

namespace App\Application\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Domain\Mail\InvoiceForClient;
use App\Domain\Model\Documents\Invoice\Invoice;
use App\Domain\Service\Documents\BillableDocumentService;
use Illuminate\Support\Facades\Mail;
use GuzzleHttp\Client;

class GenerateInvoicePdf implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $invoice;

    public function __construct(Invoice $invoice)
    {
        $this->invoice = $invoice;
    }

    public function handle(BillableDocumentService $billableDocumentService)
    {
        $billableDocumentService->genPdf($this->invoice, true);
    }
}