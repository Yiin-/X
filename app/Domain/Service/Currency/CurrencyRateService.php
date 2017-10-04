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
        $this->cacheCurrencies();
    }

    public function cacheCurrencies()
    {
        $this->currencyById = Currency::all()->mapWithKeys(function ($currency) {
            return [$currency->id => $currency];
        });
    }

    public function fetchRates()
    {
        try {
            $res = $this->httpClient->request('GET', 'http://api.fixer.io/latest?base=EUR');

            return json_decode($res->getBody())->rates;
        } catch (RequestException $e) {
            Log::error('Currency update request failed:');
            Log::error($e->getMessage());
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
        $rates = $this->fetchRates();

        foreach  ($rates as $code => $rate) {
            Currency::where('code', $code)->update([
                'eur_rate' => $rate
            ]);
        }
        $this->cacheCurrencies();
    }

    public function getCurrency($id)
    {
        if (array_key_exists($id, $this->currencyById)) {
            return $this->currencyById[$id];
        }
    }

    /**
     * Convert currency
     * @param  float $amount Amount of currency, to convert
     * @param  string $from   Currency id, the amount is currency presented as.
     * @param  string $to     Currency id, the amount should be presented as.
     * @return float          Calculated amount in specified currency
     */
    public function convert($amount, $from, $to)
    {
        if ($from === $to) {
            return $amount;
        }

        $fromRate = $this->getCurrency($from)->eur_rate;
        $toRate = $this->getCurrency($to)->eur_rate;

        // if amount is not presented in euros yet, do it now
        if ($from->code !== 'EUR') {
            $amount = bcmul($fromRate, $amount, 6);
        }

        // convert amount to desired currency
        return bcdiv($amount, $toRate, 6);
    }
}