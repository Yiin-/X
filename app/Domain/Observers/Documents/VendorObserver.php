<?php

namespace App\Domain\Observers\Documents;

use App\Domain\Model\Documents\Vendor\Vendor;

use App\Domain\Observers\Documents\Traits\DisablesChildren;

class VendorObserver
{
    use DisablesChildren;

    protected $children = [
        'expenses'
    ];
}