<?php

if (!function_exists('convert_currency')) {
    function convert_currency($amount, $from, $to) {
        return app(\App\Domain\Service\Currency\CurrencyRateService::class)->convert($amount, $from, $to);
    }
}