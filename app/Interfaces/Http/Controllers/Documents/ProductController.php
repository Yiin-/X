<?php

namespace App\Interfaces\Http\Controllers\Documents;

use App\Interfaces\Http\Controllers\DocumentController;
use App\Domain\Model\Documents\Product\ProductRepository;

class ProductController extends DocumentController
{
    protected $repository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->repository = $productRepository;
    }

    public function getResourceName()
    {
        return 'product';
    }

    public function getValidationRules($action)
    {
        $rules = [
            static::VALIDATION_RULES_CREATE => [
                $this->getResourceName() => 'required|array',
                "{$this->getResourceName()}.name" => 'required',
                "{$this->getResourceName()}.price" => 'required|numeric',
                "{$this->getResourceName()}.currency_code" => 'required|exists:currencies,code',
                "{$this->getResourceName()}.qty" => 'nullable|numeric'
            ],
            static::VALIDATION_RULES_PATCH => [
                $this->getResourceName() => 'required|array',
                "{$this->getResourceName()}.price" => 'numeric',
                "{$this->getResourceName()}.currency_code" => 'exists:currencies,code',
                "{$this->getResourceName()}.qty" => 'nullable|numeric'
            ]
        ];
        $rules[static::VALIDATION_RULES_UPDATE] = $rules[static::VALIDATION_RULES_CREATE];

        return $rules[$action];
    }

    public function getValidationAttributes()
    {
        return [
            "{$this->getResourceName()}.name" => 'product\'s name',
            "{$this->getResourceName()}.price" => 'product\'s price',
            "{$this->getResourceName()}.currency_code" => 'product\'s currency',
            "{$this->getResourceName()}.qty" => 'product\'s quantity'
        ];
    }
}