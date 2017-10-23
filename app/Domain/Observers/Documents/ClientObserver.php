<?php

namespace App\Domain\Observers\Documents;

use App\Domain\Model\Documents\Client\Client;

use App\Domain\Observers\Documents\Traits\DisablesChildren;

class ClientObserver
{
    use DisablesChildren;

    protected $children = [
        'invoices',
        'payments',
        'credits',
        'quotes',
        'expenses',
        'projects'
    ];
}