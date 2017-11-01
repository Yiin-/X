<?php

if (!function_exists('convert_currency')) {
    function convert_currency($amount, $from, $to, $precision = 2) {
        return round(app(\App\Domain\Service\Currency\CurrencyRateService::class)->convert($amount, $from, $to), $precision);
    }
}