<?php

namespace App\Interfaces\Http\Controllers\Documents;

use App\Interfaces\Http\Controllers\DocumentController;
use App\Domain\Model\Documents\Expense\ExpenseRepository;

class ExpenseController extends DocumentController
{
    protected $repository;

    public function __construct(ExpenseRepository $expenseRepository)
    {
        $this->repository = $expenseRepository;
    }

    public function getResourceName()
    {
        return 'expense';
    }

    public function getValidationRules($action)
    {
        $rules = [
            static::VALIDATION_RULES_CREATE => [
                $this->getResourceName() => 'required|array',
                "{$this->getResourceName()}.vendor_uuid" => 'required|exists:vendors,uuid',
                "{$this->getResourceName()}.client_uuid" => 'required|exists:clients,uuid',
                // "{$this->getResourceName()}.category_uuid" => 'required|exists:expense_categories,uuid',
                "{$this->getResourceName()}.amount" => 'required',
                "{$this->getResourceName()}.currency_code" => 'required|exists:currencies,code',
                "{$this->getResourceName()}.date" => 'nullable|date'
            ],
            static::VALIDATION_RULES_PATCH => [
                $this->getResourceName() => 'required|array',
                "{$this->getResourceName()}.vendor_uuid" => 'exists:vendors,uuid',
                "{$this->getResourceName()}.client_uuid" => 'exists:clients,uuid',
                "{$this->getResourceName()}.currency_code" => 'exists:currencies,code',
                "{$this->getResourceName()}.date" => 'nullable|date'
            ]
        ];
        $rules[static::VALIDATION_RULES_UPDATE] = $rules[static::VALIDATION_RULES_CREATE];

        return $rules[$action];
    }

    public function getValidationAttributes()
    {
        return [
            "{$this->getResourceName()}.vendor_uuid" => 'vendor',
            "{$this->getResourceName()}.client_uuid" => 'client',
            "{$this->getResourceName()}.category_uuid" => 'category',
            "{$this->getResourceName()}.amount" => 'expense\'s amount',
            "{$this->getResourceName()}.currency_code" => 'expense\'s currency',
            "{$this->getResourceName()}.date" => 'expense\'s date'
        ];
    }
}