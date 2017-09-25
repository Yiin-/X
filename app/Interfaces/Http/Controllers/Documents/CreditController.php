<?php

namespace App\Interfaces\Http\Controllers\Documents;

use App\Interfaces\Http\Controllers\DocumentController;
use App\Domain\Model\Documents\Credit\CreditRepository;

class CreditController extends DocumentController
{
    protected $repository;

    public function __construct(CreditRepository $creditRepository)
    {
        $this->repository = $creditRepository;
    }

    public function getResourceName()
    {
        return 'credit';
    }

    public function getValidationRules($action)
    {
        $rules = [
            static::VALIDATION_RULES_CREATE => [
                $this->getResourceName() => 'required|array',
                "{$this->getResourceName()}.client_uuid" => 'required|exists:clients,uuid',
                "{$this->getResourceName()}.amount" => 'required',
                "{$this->getResourceName()}.currency_id" => 'required|exists:currencies,id',
                "{$this->getResourceName()}.credit_date" => 'nullable|date'
            ],
            static::VALIDATION_RULES_PATCH => [
                $this->getResourceName() => 'required|array',
                "{$this->getResourceName()}.client_uuid" => 'exists:clients,uuid',
                "{$this->getResourceName()}.currency_id" => 'exists:currencies,id',
                "{$this->getResourceName()}.credit_date" => 'nullable|date'
            ]
        ];
        $rules[static::VALIDATION_RULES_UPDATE] = $rules[static::VALIDATION_RULES_CREATE];

        return $rules[$action];
    }

    public function getValidationAttributes()
    {
        return [
            "{$this->getResourceName()}.client_uuid" => 'client',
            "{$this->getResourceName()}.amount" => 'credit\'s amount',
            "{$this->getResourceName()}.currency_id" => 'credit\'s currency',
            "{$this->getResourceName()}.credit_date" => 'credit\'s date'
        ];
    }
}