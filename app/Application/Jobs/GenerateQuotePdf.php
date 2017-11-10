<?php

namespace App\Application\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Domain\Mail\QuoteForClient;
use App\Domain\Model\Documents\Quote\Quote;
use App\Domain\Service\Documents\BillableDocumentService;
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

    public function handle(BillableDocumentService $billableDocumentService)
    {
        $billableDocumentService->genPdf($this->quote, true);
    }
}