<?php

namespace App\Domain\Model\Features\VatChecker;

use App\Domain\Model\Documents\Shared\AbstractDocument;

class VatInfo extends AbstractDocument
{
    protected $table = 'vat_checks';
    protected $primaryKey = 'id';
    public $incrementing = true;

    protected $fillable = [
        'user_uuid',
        'name',
        'address',
        'status',
        'country_code',
        'number',
        'message'
    ];

    public function getTransformer()
    {
        return new VatInfoTransformer;
    }
}