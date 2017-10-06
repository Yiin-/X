<?php

namespace App\Application\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Domain\Listeners\DocumentPermissionsListener;
use App\Domain\Listeners\ActivityListener;
use \App\Domain\Model\Documents\Invoice\Invoice;
use \App\Domain\Observers\Documents\InvoiceObserver;

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

        Invoice::observe(InvoiceObserver::class);
    }
}
