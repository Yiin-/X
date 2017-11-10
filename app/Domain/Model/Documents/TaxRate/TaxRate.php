<?php

namespace App\Domain\Model\Documents\TaxRate;

use App\Domain\Model\Documents\Client\Client;
use App\Domain\Model\Documents\Passive\Currency;
use App\Domain\Model\Documents\Shared\AbstractDocument;
use Illuminate\Database\Eloquent\SoftDeletes;

class TaxRate extends AbstractDocument
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'rate',
        'is_inclusive'
    ];

    protected $hidden = [
        'user_uuid',
        'company_uuid'
    ];

    public function getTransformer()
    {
        return new TaxRateTransformer;
    }
}