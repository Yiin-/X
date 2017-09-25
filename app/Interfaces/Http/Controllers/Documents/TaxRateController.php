<?php

namespace App\Interfaces\Http\Controllers\Documents;

use App\Interfaces\Http\Controllers\DocumentController;
use App\Domain\Model\Documents\TaxRate\TaxRateRepository;

class TaxRateController extends DocumentController
{
    protected $repository;

    public function __construct(TaxRateRepository $taxRateRepository)
    {
        $this->repository = $taxRateRepository;
    }

    public function getResourceName()
    {
        return 'tax-rate';
    }

    public function getValidationRules($action)
    {
        $rules = [
            static::VALIDATION_RULES_CREATE => [
                $this->getResourceName() => 'required|array',
                "{$this->getResourceName()}.name" => 'required',
                "{$this->getResourceName()}.rate" => 'required|numeric'
            ],
            static::VALIDATION_RULES_PATCH => [
                $this->getResourceName() => 'required|array',
                "{$this->getResourceName()}.rate" => 'numeric'
            ]
        ];
        $rules[static::VALIDATION_RULES_UPDATE] = $rules[static::VALIDATION_RULES_CREATE];

        return $rules[$action];
    }

    public function getValidationAttributes()
    {
        return [
            "{$this->getResourceName()}.name" => 'name',
            "{$this->getResourceName()}.rate" => 'rate'
        ];
    }
}