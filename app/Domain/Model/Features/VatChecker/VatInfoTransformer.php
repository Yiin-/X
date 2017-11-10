<?php

namespace App\Domain\Model\Features\VatChecker;

use League\Fractal;

class VatInfoTransformer extends Fractal\TransformerAbstract
{
    public function transform(VatInfo $vatInfo)
    {
        return [
            'name' => $vatInfo->name,
            'address' => $vatInfo->address,
            'status' => $vatInfo->status,
            'country_code' => $vatInfo->country_code,
            'number' => $vatInfo->number,
            'message' => $vatInfo->message,

            'created_at' => $vatInfo->created_at
        ];
    }
}