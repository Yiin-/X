<?php

namespace App\Domain\Observers\Documents;

use App\Application\Jobs\GenerateQuotePdf;
use App\Domain\Model\Documents\Quote\Quote;
use App\Domain\Observers\Documents\Traits\DisablesChildren;

class QuoteObserver
{
    use DisablesChildren;

    protected $children = [
        'payments'
    ];

    public function saved(Quote $quote)
    {
        // Quote was not changed in any way
        if (!$quote->isDirty()) {
            return;
        }

        \Log::debug('Generating pdf for quote #' . $quote->quote_number);
        GenerateQuotePdf::dispatch($quote);
    }
}