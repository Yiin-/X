<?php

namespace App\Domain\Model\Documents\Bill;

use League\Fractal;
use App\Domain\Model\Documents\Credit\AppliedCreditTransformer;
use App\Domain\Model\Documents\Product\ProductTransformer;

class BillItemTransformer extends Fractal\TransformerAbstract
{
    public function transform(BillItem $billItem)
    {
        return [
            'product_uuid' => $billItem->product_uuid,
            'name' => $billItem->name,
            'identification_number' => $billItem->identification_number,
            'cost' => +$billItem->cost,
            'discount' => +$billItem->discount,
            'qty' => +$billItem->qty,
            'tax_rate' => $billItem->taxRate,
            'index' => $billItem->index
        ];
    }
}