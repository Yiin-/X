<?php

namespace App\Interfaces\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

use App\Application\Jobs\UpdateCurrencyRates;
use App\Application\Jobs\SendRecurringInvoices;
use App\Application\Jobs\SendMarkedInvoices;
use App\Application\Jobs\SendMarkedQuotes;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->job(new UpdateCurrencyRates)->hourly();
        $schedule->job(new SendRecurringInvoices)->everyMinute();
        $schedule->job(new SendMarkedInvoices)->everyMinute();
        $schedule->job(new SendMarkedQuotes)->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
