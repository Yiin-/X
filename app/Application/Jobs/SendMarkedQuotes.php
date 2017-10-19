<?php

namespace App\Application\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Domain\Constants\Quote\Statuses;
use App\Domain\Mail\QuoteForClient;
use App\Domain\Model\Documents\Quote\QuoteRepository;

class SendMarkedQuotes implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(QuoteRepository $quoteRepository)
    {
        foreach ($quoteRepository->getMarkedToBeSent() as $quote) {
            SendQuote::dispatch($quote);
        }
    }
}