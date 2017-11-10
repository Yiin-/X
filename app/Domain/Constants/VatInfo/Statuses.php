<?php

namespace App\Domain\Constants\VatInfo;

class Statuses
{
    const VALID = 'valid';
    const PENDING = 'pending';
    const INVALID = 'invalid';

    const LIST = [
        self::VALID,
        self::PENDING,
        self::INVALID
    ];
}