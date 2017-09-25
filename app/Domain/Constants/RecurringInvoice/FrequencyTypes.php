<?php

namespace App\Domain\Constants\RecurringInvoice;

class FrequencyTypes
{
    const WEEK = 'week';
    const MONTH = 'month';
    const YEAR = 'year';

    const LIST = [
        self::WEEK,
        self::MONTH,
        self::YEAR
    ];
}