<?php

namespace App\Interfaces\Http\Controllers\Documents;

use App\Interfaces\Http\Controllers\DocumentController;
use App\Domain\Model\Documents\RecurringInvoice\RecurringInvoiceRepository;

class RecurringInvoiceController extends DocumentController
{
    protected $repository;

    public function __construct(RecurringInvoiceRepository $recurringInvoiceRepository)
    {
        $this->repository = $recurringInvoiceRepository;
    }

    public function getResourceName()
    {
        return 'recurring-invoice';
    }

    public function getValidationRules($action)
    {
        $rules = [
            static::VALIDATION_RULES_CREATE => [
                $this->getResourceName() => 'required|array',
                "{$this->getResourceName()}.client_uuid" => 'required|exists:clients,uuid',
                "{$this->getResourceName()}.start_date" => 'nullable|date',
                "{$this->getResourceName()}.end_date" => 'nullable|date',
                "{$this->getResourceName()}.items" => "array",
                "{$this->getResourceName()}.items.*.product_uuid" => 'required|exists:products,uuid'
                // "{$this->getResourceName()}.discount.type" => '',
            ],
            static::VALIDATION_RULES_PATCH => [
                $this->getResourceName() => 'required|array',
                "{$this->getResourceName()}.client_uuid" => 'exists:clients,uuid',
                "{$this->getResourceName()}.start_date" => 'nullable|date',
                "{$this->getResourceName()}.end_date" => 'nullable|date',
                "{$this->getResourceName()}.items" => "array",
                "{$this->getResourceName()}.items.*.product_uuid" => 'exists:products,uuid'
            ]
        ];
        $rules[static::VALIDATION_RULES_UPDATE] = $rules[static::VALIDATION_RULES_CREATE];

        return $rules[$action];
    }

    public function getValidationAttributes()
    {
        return [
            "{$this->getResourceName()}.client_uuid" => 'client',
            "{$this->getResourceName()}.quote_date" => 'quote\'s date',
            "{$this->getResourceName()}.due_date" => 'due date',
            "{$this->getResourceName()}.partial" => 'partial'
        ];
    }
}