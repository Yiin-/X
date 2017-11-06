<?php

namespace App\Application\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Domain\Listeners\DocumentPermissionsListener;
use App\Domain\Listeners\ActivityListener;
use \App\Domain\Model\Documents\Invoice\Invoice;
use \App\Domain\Model\Documents\Quote\Quote;
use \App\Domain\Model\Documents\Client\Client;
use \App\Domain\Model\Documents\Pdf\Pdf;
use \App\Domain\Model\Authentication\User\User;
use \App\Domain\Observers\Documents\InvoiceObserver;
use \App\Domain\Observers\Documents\QuoteObserver;
use \App\Domain\Observers\Documents\ClientObserver;
use \App\Domain\Observers\Documents\PdfObserver;
use \App\Domain\Observers\Authentication\UserObserver;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [];

    protected $subscribe = [
        ActivityListener::class
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        Client::observe(ClientObserver::class);
        Invoice::observe(InvoiceObserver::class);
        Quote::observe(QuoteObserver::class);
        User::observe(UserObserver::class);
        Pdf::observe(PdfObserver::class);
    }
}
