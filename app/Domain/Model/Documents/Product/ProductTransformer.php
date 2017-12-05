<?php

namespace App\Domain\Model\Documents\Product;

use League\Fractal;
use App\Domain\Model\System\ActivityLog\ActivityTransformer;

class ProductTransformer extends Fractal\TransformerAbstract
{
    protected $defaultIncludes = [
        'history'
    ];

    public function excludeForBackup()
    {
        return ['history'];
    }

    public function transform(Product $product)
    {
        return [
            'uuid' => $product->uuid,
            'company_uuid' => $product->company_uuid,

            'name' => $product->name,
            'qty' => +$product->qty,
            'identification_number' => $product->identification_number,
            'description' => $product->description,

            'price' => +$product->price,
            'currency_code' => $product->currency_code,
            'tax_rate' => $product->taxRate,

            'is_service' => $product->qty === null,
            'is_disabled' => $product->is_disabled,

            'created_at' => $product->created_at,
            'updated_at' => $product->updated_at,
            'deleted_at' => $product->deleted_at,
            'archived_at' => $product->archived_at
        ];
    }

    public function includeHistory(Product $product)
    {
        return $this->collection($product->getHistory(), new ActivityTransformer);
    }
}