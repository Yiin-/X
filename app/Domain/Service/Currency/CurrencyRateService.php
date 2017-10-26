<?php

namespace App\Domain\Service\Currency;

use GuzzleHttp\Client;
use App\Domain\Model\Documents\Passive\Currency;

class CurrencyRateService
{
    protected $httpClient;
    protected $currencyById;

    public function __construct()
    {
        $this->httpClient = new Client;
    }

    public function fetchRates($base)
    {
        try {
            $res = $this->httpClient->request('GET', 'http://api.fixer.io/latest?base=' . $base);

            return json_decode($res->getBody())->rates;
        } catch (\Exception $e) {
            \Log::error('CurrencyRateService: ' . $base . ' rates update failed:');
            \Log::error($e->getMessage());
        }

        return [];
    }

    /**
     * Update currency rates in database
     * @param  array  $data Array of currency rates in ['code' => $rate] format
     * @return void
     */
    public function updateRates()
    {
        foreach (Currency::all() as $currency) {
            $rates = $this->fetchRates($currency->code);

            foreach ($rates as $code => $rate) {
                $currency->rates()->updateOrCreate([
                    'to' => $code
                ], [
                    'rate' => $rate
                ]);
            }
        }
    }
}