<?php

namespace App\Domain\Constants\Invoice;

class Statuses
{
    const DRAFT = 'draft';
    const PENDING = 'pending';
    const SENT = 'sent';
    const VIEWED = 'viewed';
    const APPROVED = 'approved';
    const PARTIAL = 'partial';
    const PAID = 'paid';

    const LIST = [
        self::DRAFT,
        self::PENDING,
        self::SENT,
        self::VIEWED,
        self::APPROVED,
        self::PARTIAL,
        self::PAID
    ];
}