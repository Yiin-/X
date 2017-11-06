<?php

namespace App\Domain\Service\Currency;

use GuzzleHttp\Client;
use App\Domain\Model\Documents\Passive\Currency;
use Redis;

class CurrencyRateService
{
    protected $httpClient;
    protected $currencyById;

    public function __construct()
    {
        $this->httpClient = new Client;
    }

    public function convert($amount, $from, $to)
    {
        if ($from === $to) {
            return $amount;
        }

        $rate = Redis::get('currency-rate:' . $from . ':' . $to);

        if ($rate) {
            return bcmul($amount, $rate, 2);
        }
        throw new \Exception('Could not find conversion rate ' . $from . ' -> ' . $to);
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
            echo 'Fetching rates for ' . $currency->code . PHP_EOL;
            $rates = $this->fetchRates($currency->code);

            Redis::pipeline(function ($pipe) use ($currency, $rates) {
                foreach ($rates as $code => $rate) {
                    $pipe->set("currency-rate:{$currency->code}:$code", $rate);

                    $currency->rates()->updateOrCreate([
                        'to' => $code
                    ], [
                        'rate' => $rate
                    ]);
                }
            });
        }
    }
}