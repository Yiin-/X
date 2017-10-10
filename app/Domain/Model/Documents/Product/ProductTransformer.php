<?php

namespace App\Domain\Model\Documents\Product;

use League\Fractal;
use Money\Currency;
use Money\Money;

class ProductTransformer extends Fractal\TransformerAbstract
{
    public function transform(Product $product)
    {
        return [
            'uuid' => $product->uuid,

            'name' => $product->name,
            'qty' => $product->qty,
            'identification_number' => $product->identification_number,
            'description' => $product->description,

            'price' => $product->price,
            'currency_code' => $product->currency_code,
            'tax_rate' => $product->taxRate,

            'is_service' => $product->qty === null,

            'created_at' => $product->created_at,
            'updated_at' => $product->updated_at,
            'deleted_at' => $product->deleted_at,
            'archived_at' => $product->archived_at
        ];
    }
}