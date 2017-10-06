<?php

namespace App\Application\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Domain\Mail\InvoiceForClient;
use App\Domain\Model\Documents\Invoice\Invoice;
use App\Domain\Constants\Pdf\Statuses;
use Illuminate\Support\Facades\Mail;
use dawood\phpChrome\Chrome;

class GenerateInvoicePdf implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $invoice;

    public function __construct(Invoice $invoice)
    {
        $this->invoice = $invoice;
    }

    public function handle()
    {
        // Render Invoice HTML
        $html = view('emails.invoices.default.invoice', [
            'noteToClient' => $this->invoice->bill->notes,
            'poNumber'     => $this->invoice->bill->po_number,
            'date'         => $this->invoice->bill->date,
            'items'        => $this->invoice->bill->items,
            'subTotal'     => $this->invoice->subTotal(),
            'grandTotal'   => $this->invoice->amount(),
            'discount'     => $this->invoice->discount(),
            'tax'          => $this->invoice->taxes(),
            'footerText'   => $this->invoice->bill->footer,
        ]);

        // Run chrome
        $chrome = new Chrome(null, env('CHROME_PATH'));
        $chrome->setHtml($html);

        $pathToFile = 'app/pdfs/invoices/' . $invoice->uuid . '/' . Carbon::now()->toDateTimeString() . 'pdf';
        $path = storage_path($pathToFile);

        $chrome->getPdf($path);

        $invoice->pdfs()->create([
            'path_to_pdf' => $path,
            'status' => Statuses\CREATED::class
        ]);
    }
}