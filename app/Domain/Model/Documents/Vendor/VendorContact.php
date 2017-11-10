<?php

namespace App\Domain\Model\Documents\Vendor;

use App\Domain\Model\Documents\Profile\Profile;
use App\Domain\Model\Documents\Shared\AbstractDocument;

class VendorContact extends AbstractDocument
{
    public function getTransformer()
    {
        return new VendorContactTransformer;
    }

    public function profile()
    {
        return $this->belongsTo(Profile::class);
    }
}