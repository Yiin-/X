<?php

namespace App\Domain\Constants\RecurringInvoice;

class Statuses
{
    const DRAFT = 'draft';
    const PENDING = 'pending';
    const OVERDUE = 'overdue';
    const ACTIVE = 'active';

    const LIST = [
        self::DRAFT,
        self::PENDING,
        self::OVERDUE,
        self::ACTIVE
    ];
}