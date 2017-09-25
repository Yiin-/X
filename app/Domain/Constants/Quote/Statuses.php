<?php

namespace App\Domain\Constants\Quote;

class Statuses
{
    const DRAFT = 'draft';
    const SENT = 'sent';
    const VIEWED = 'viewed';
    const APPROVED = 'approved';
    const CONVERTED = 'converted';

    const LIST = [
        self::DRAFT,
        self::SENT,
        self::VIEWED,
        self::APPROVED,
        self::CONVERTED
    ];
}