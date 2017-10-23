<?php

namespace App\Interfaces\Http\Controllers\Documents;

use App\Interfaces\Http\Controllers\DocumentController;
use App\Domain\Model\Documents\Vendor\VendorRepository;

class VendorController extends DocumentController
{
    protected $repository;

    public function __construct(VendorRepository $vendorRepository)
    {
        $this->repository = $vendorRepository;
    }

    public function getResourceName()
    {
        return 'vendor';
    }

    public function getValidationRules($action)
    {
        $rules = [
            static::VALIDATION_RULES_CREATE => [
                $this->getResourceName() => 'required|array',
                "{$this->getResourceName()}.name" => 'required',
                "{$this->getResourceName()}.country_id" => 'nullable|exists:countries,id',
                "{$this->getResourceName()}.currency_code" => 'nullable|exists:currencies,code'
            ],
            static::VALIDATION_RULES_PATCH => [
                $this->getResourceName() => 'required|array',
                "{$this->getResourceName()}.country_id" => 'nullable|exists:countries,id',
                "{$this->getResourceName()}.currency_code" => 'nullable|exists:currencies,code'
            ]
        ];
        $rules[static::VALIDATION_RULES_UPDATE] = $rules[static::VALIDATION_RULES_CREATE];

        return $rules[$action];
    }

    public function getValidationAttributes()
    {
        return [
            "{$this->getResourceName()}.name" => 'company name',
            "{$this->getResourceName()}.country_id" => 'vendor\'s country',
            "{$this->getResourceName()}.currency_code" => 'vendor\'s currency'
        ];
    }
}