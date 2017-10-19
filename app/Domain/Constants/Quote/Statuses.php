<?php

namespace App\Domain\Constants\Quote;

class Statuses
{
    const DRAFT = 'draft';
    const PENDING = 'pending';
    const SENT = 'sent';
    const VIEWED = 'viewed';
    const APPROVED = 'approved';
    const CONVERTED = 'converted';

    const LIST = [
        self::DRAFT,
        self::PENDING,
        self::SENT,
        self::VIEWED,
        self::APPROVED,
        self::CONVERTED
    ];
}