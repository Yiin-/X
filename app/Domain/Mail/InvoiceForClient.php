<?php

namespace App\Domain\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Domain\Model\Documents\Invoice\Invoice;

class InvoiceForClient extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $invoice;
    public $client;
    public $company;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Invoice $invoice)
    {
        $this->invoice = $invoice;
        $this->client = $invoice->client;
        $this->company = $invoice->company;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.invoice.default')
            ->attach(storage_path($this->invoice->pdfs()->latest()->path_to_pdf), [
                'as' => $this->invoice->bill->number . '.pdf',
                'mime' => 'application/pdf'
            ]);
    }
}
