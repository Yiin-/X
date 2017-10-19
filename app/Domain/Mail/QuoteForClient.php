<?php

namespace App\Domain\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Domain\Model\Documents\Quote\Quote;
use App\Domain\Constants\Quote\Statuses;

class QuoteForClient extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $quote;
    public $client;
    public $company;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Quote $quote)
    {
        $this->quote = $quote;
        $this->client = $quote->client;
        $this->company = $quote->company;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if ($this->quote->status === Statuses::PENDING) {
            $this->quote->update([
                'status' => Statuses::SENT
            ]);
        }
        return $this->markdown('emails.quote.default')
            ->attach(storage_path($this->quote->pdfs()->latest()->path_to_pdf), [
                'as' => $this->quote->bill->number . '.pdf',
                'mime' => 'application/pdf'
            ]);
    }
}
