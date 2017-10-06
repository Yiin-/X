<?php

namespace App\Domain\Constants\Pdf;

class Statuses
{
    const PENDING = 'pending';
    const CREATED = 'created';

    const LIST = [
        self::PENDING,
        self::CREATED
    ];
}