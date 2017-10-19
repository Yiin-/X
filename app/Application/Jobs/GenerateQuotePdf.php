<?php

namespace App\Application\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Domain\Mail\QuoteForClient;
use App\Domain\Model\Documents\Quote\Quote;
use App\Domain\Constants\Pdf\Statuses;
use Illuminate\Support\Facades\Mail;
use GuzzleHttp\Client;

class GenerateQuotePdf implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $quote;

    public function __construct(Quote $quote)
    {
        $this->quote = $quote;
    }

    public function handle()
    {
        // Render Quote HTML
        $html = view('pdfs.quote.default.quote', [
            'noteToClient' => $this->quote->bill->notes,
            'poNumber'     => $this->quote->bill->po_number,
            'date'         => \Carbon\Carbon::parse($this->invoice->bill->date)->format('d-m-Y'),
            'items'        => $this->quote->bill->items,
            'subTotal'     => number_format($this->quote->subTotal(), 2),
            'grandTotal'   => number_format($this->quote->amount(), 2),
            'discount'     => number_format($this->quote->discount(), 2),
            'tax'          => number_format($this->quote->taxes(), 2),
            'footerText'   => $this->quote->bill->footer,
            'currencySymbol' => $this->quote->bill->currency->symbol
        ]);

        $pathToFile = 'app/pdfs/quotes/' . $this->quote->uuid . '/' . \Carbon\Carbon::now()->format('Y-m-d H-i-s') . '.pdf';
        $path = storage_path($pathToFile);

        $httpClient = new Client;

        try {
            $response = $httpClient->request('POST', config('node-server.url.html_to_pdf'), [
                'json' => [
                    'save_to_path' => $path,
                    'html' => (string)$html
                ]
            ]);

            $this->quote->pdfs()->create([
                'path_to_pdf' => $pathToFile,
                'status' => Statuses::CREATED
            ]);
        }
        catch (\Exception $e) {
            \Log::error($e->getMessage());
        }
    }
}