<?php

namespace App\Domain\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Domain\Model\Documents\Invoice\Invoice;

class InvoiceForClient extends Mailable
{
    use Queueable, SerializesModels;

    public $noteToClient;
    public $poNumber;
    public $date;
    public $items;
    public $subTotal;
    public $grandTotal;
    public $discount;
    public $tax;
    public $footerText;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Invoice $invoice)
    {
        $this->noteToClient = $invoice->bill->notes;
        $this->poNumber = $invoice->bill->po_number;
        $this->date = $invoice->bill->date;
        $this->items = $invoice->bill->items;
        $this->subTotal = $invoice->subTotal();
        $this->grandTotal = $invoice->amount();
        $this->discount = $invoice->discount();
        $this->tax = $invoice->taxes();
        $this->footerText = $invoice->bill->footer;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.invoices.default');
    }
}
