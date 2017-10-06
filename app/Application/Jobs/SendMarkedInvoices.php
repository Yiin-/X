<?php

namespace App\Application\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Domain\Constants\Invoice\Statuses;
use App\Domain\Mail\InvoiceForClient;

class SendMarkedInvoices implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(InvoiceRepository $invoiceRepository)
    {
        foreach ($invoiceRepository->getMarkedToBeSent() as $invoice) {
            SendInvoice::dispatch($invoice);
        }
    }
}