<?php

namespace App\Interfaces\Console\Commands;

use Illuminate\Console\Command;
use App\Application\Jobs\UpdateCurrencyRates;
use App\Domain\Service\Currency\CurrencyRateService;

class UpdateCurrencyRatesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'currency-rates:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates currency rates';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(CurrencyRateService $currencyRateService)
    {
        $currencyRateService->updateRates();
    }
}
