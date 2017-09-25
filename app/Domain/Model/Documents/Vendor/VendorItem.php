<?php

namespace App\Domain\Model\Documents\Vendor;

use App\Domain\Model\Documents\Shared\AbstractDocument;
use App\Domain\Model\Document\Vendor\Vendor;

class VendorProduct extends AbstractDocument
{
    protected $fillable = [
        'name',
        'description',
        'price'
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
}