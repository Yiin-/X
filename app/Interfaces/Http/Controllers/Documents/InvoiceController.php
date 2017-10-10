<?php

namespace App\Interfaces\Http\Controllers\Documents;

use App\Interfaces\Http\Controllers\DocumentController;
use App\Domain\Model\Documents\Invoice\InvoiceRepository;

class InvoiceController extends DocumentController
{
    protected $repository;

    public function __construct(InvoiceRepository $invoiceRepository)
    {
        $this->repository = $invoiceRepository;
    }

    public function getResourceName()
    {
        return 'invoice';
    }

    public function getValidationRules($action)
    {
        $rules = [
            static::VALIDATION_RULES_CREATE => [
                $this->getResourceName() => 'required|array',
                "{$this->getResourceName()}.quote_uuid" => 'nullable|exists:quotes,uuid',
                "{$this->getResourceName()}.client_uuid" => 'required|exists:clients,uuid',
                "{$this->getResourceName()}.invoice_date" => 'nullable|date',
                "{$this->getResourceName()}.due_date" => 'nullable|date',
                "{$this->getResourceName()}.partial" => 'nullable|numeric',
                "{$this->getResourceName()}.currency_code" => 'nullable|exists:currencies,code',
                "{$this->getResourceName()}.items" => "array",
                "{$this->getResourceName()}.items.*.product_uuid" => 'exists:products,uuid'
                // "{$this->getResourceName()}.discount.type" => '',
            ],
            static::VALIDATION_RULES_PATCH => [
                "{$this->getResourceName()}.client_uuid" => 'exists:clients,uuid',
                "{$this->getResourceName()}.invoice_date" => 'nullable|date',
                "{$this->getResourceName()}.due_date" => 'nullable|date',
                "{$this->getResourceName()}.partial" => 'nullable|numeric',
                "{$this->getResourceName()}.currency_code" => 'nullable|exists:currencies,code',
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
            "{$this->getResourceName()}.invoice_date" => 'invoice\'s date',
            "{$this->getResourceName()}.due_date" => 'due date',
            "{$this->getResourceName()}.partial" => 'partial'
        ];
    }
}