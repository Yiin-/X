<?php

namespace App\Domain\Constants\Bill;

class DiscountTypes
{
    const PERCENTAGE = 'percentage';
    const FLAT = 'flat';

    const LIST = [
        self::PERCENTAGE,
        self::FLAT
    ];
}