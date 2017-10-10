<?php

namespace App\Application\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Domain\Constants\Invoice\Statuses;
use App\Domain\Mail\InvoiceForClient;
use App\Domain\Model\Documents\Invoice\Invoice;
use Illuminate\Support\Facades\Mail;

class SendInvoice implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 5;

    /**
     * Invoice that needs to be sent
     * @var Invoice
     */
    public $invoice;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Invoice $invoice)
    {
        $invoice->loadMissing(['client', 'bill', 'company']);
        $this->invoice = $invoice;
    }

    /**
     * Try to send an email, with attached invoice
     * to client's primary address
     *
     * @return void
     */
    public function handle()
    {
        $client = $this->invoice->client;

        if (!$client) {
            // TODO: Log an error and notify an user,
            // that invoice could not be sent
            // because there is no client assigned to the invoice.
            return;
        }

        if (!$client->hasPrimaryEmail()) {
            // TODO: Log an error and notify an user,
            // that invoice could not be sent
            // because assigned client has no primary email set
            return;
        }

        // Send an invoice
        Mail::to($client->primary_email)
            ->send(new InvoiceForClient($this->invoice));
    }

    public function failed(Exception $exception)
    {
        \Log::error($exception->getMessage());
    }
}
