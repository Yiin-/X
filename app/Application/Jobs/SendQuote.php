<?php

namespace App\Application\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Domain\Constants\Quote\Statuses;
use App\Domain\Mail\QuoteForClient;
use App\Domain\Model\Documents\Quote\Quote;
use Illuminate\Support\Facades\Mail;

class SendQuote implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 5;

    /**
     * Quote that needs to be sent
     * @var Quote
     */
    public $quote;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Quote $quote)
    {
        $quote->loadMissing(['client', 'bill', 'company']);
        $this->quote = $quote;
    }

    /**
     * Try to send an email, with attached quote
     * to client's primary address
     *
     * @return void
     */
    public function handle()
    {
        $client = $this->quote->client;

        if (!$client) {
            // TODO: Log an error and notify an user,
            // that quote could not be sent
            // because there is no client assigned to the quote.
            return;
        }

        if (!$client->hasPrimaryEmail()) {
            // TODO: Log an error and notify an user,
            // that quote could not be sent
            // because assigned client has no primary email set
            return;
        }

        // Send an quote
        Mail::to($client->primary_email)
            ->send(new QuoteForClient($this->quote));
    }

    public function failed(Exception $exception)
    {
        \Log::error($exception->getMessage());
    }
}
