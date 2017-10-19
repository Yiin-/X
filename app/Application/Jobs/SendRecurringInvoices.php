<?php

namespace App\Application\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Domain\Constants\Invoice\Statuses;
use App\Domain\Mail\InvoiceForClient;
use App\Domain\Model\Documents\RecurringInvoice\RecurringInvoiceRepository;
use App\Domain\Model\Documents\Invoice\InvoiceRepository;

class SendRecurringInvoices implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(
        RecurringInvoiceRepository $recurringInvoiceRepository,
        InvoiceRepository $invoiceRepository
    ) {
        foreach ($recurringInvoiceRepository->getActiveAndReadyToBeSent() as $recurringInvoice) {
            CreateRecurredInvoice::dispatch($recurringInvoice);
        }
    }
}