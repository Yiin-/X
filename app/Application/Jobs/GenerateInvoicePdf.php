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
use GuzzleHttp\Client;

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
        $html = view('pdfs.invoice.default.invoice', [
            'noteToClient' => $this->invoice->bill->notes,
            'poNumber'     => $this->invoice->bill->po_number,
            'date'         => \Carbon\Carbon::parse($this->invoice->bill->date)->format('d/m/Y'),
            'items'        => $this->invoice->bill->items,
            'subTotal'     => $this->invoice->subTotal(),
            'grandTotal'   => $this->invoice->amount(),
            'discount'     => $this->invoice->discount(),
            'tax'          => $this->invoice->taxes(),
            'footerText'   => $this->invoice->bill->footer,
            'currencySymbol' => $this->invoice->bill->currency->symbol
        ]);

        $filename = \Carbon\Carbon::now()->format('Y-m-d H-i-s') . '.pdf';
        $pathToFile = 'app/pdfs/invoices/' . $this->invoice->uuid . '/' . $filename;
        $path = storage_path($pathToFile);

        $httpClient = new Client;

        try {
            $response = $httpClient->request('POST', config('node-server.url.html_to_pdf'), [
                'json' => [
                    'save_to_path' => $path,
                    'html' => (string)$html
                ]
            ]);

            $this->invoice->pdfs()->create([
                'filename' => $filename,
                'path_to_pdf' => $pathToFile,
                'status' => Statuses::CREATED
            ]);
        }
        catch (\Exception $e) {
            \Log::error($e->getMessage());
        }
    }
}