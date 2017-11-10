<?php

namespace App\Domain\Model\Documents\TaxRate;

use League\Fractal;
use App\Domain\Model\System\ActivityLog\ActivityTransformer;

class TaxRateTransformer extends Fractal\TransformerAbstract
{
    protected $defaultIncludes = [
        'history'
    ];

    public function transform(TaxRate $taxRate)
    {
        return [
            'uuid' => $taxRate->uuid,

            'name' => $taxRate->name,
            'rate' => +$taxRate->rate,
            'is_inclusive' => $taxRate->is_inclusive,

            'created_at' => $taxRate->created_at->toDateString()
        ];
    }

    public function includeHistory(TaxRate $taxRate)
    {
        return $this->collection($taxRate->getHistory(), new ActivityTransformer);
    }
}