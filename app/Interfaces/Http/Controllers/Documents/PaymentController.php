<?php

namespace App\Interfaces\Http\Controllers\Documents;

use App\Interfaces\Http\Controllers\DocumentController;
use App\Domain\Model\Documents\Payment\PaymentRepository;

class PaymentController extends DocumentController
{
    protected $repository;

    public function __construct(PaymentRepository $paymentRepository)
    {
        $this->repository = $paymentRepository;
    }

    public function getResourceName()
    {
        return 'payment';
    }

    public function getValidationRules($action)
    {
        $rules = [
            static::VALIDATION_RULES_CREATE => [
                $this->getResourceName() => 'required|array',
                "{$this->getResourceName()}.client_uuid" => 'required|exists:clients,uuid',
                "{$this->getResourceName()}.invoice_uuid" => 'required|exists:invoices,uuid',
                "{$this->getResourceName()}.amount" => 'required',
                "{$this->getResourceName()}.currency_id" => 'required|exists:currencies,id',
                "{$this->getResourceName()}.payment_type_id" => 'nullable|exists:payment_types,id',
                "{$this->getResourceName()}.payment_date" => 'nullable|date'
            ],
            static::VALIDATION_RULES_PATCH => [
                $this->getResourceName() => 'required|array',
                "{$this->getResourceName()}.client_uuid" => 'exists:clients,uuid',
                "{$this->getResourceName()}.invoice_uuid" => 'exists:invoices,uuid',
                "{$this->getResourceName()}.currency_id" => 'exists:currencies,id',
                "{$this->getResourceName()}.payment_type_id" => 'nullable|exists:payment_types,id',
                "{$this->getResourceName()}.payment_date" => 'nullable|date'
            ]
        ];
        $rules[static::VALIDATION_RULES_UPDATE] = $rules[static::VALIDATION_RULES_CREATE];

        return $rules[$action];
    }

    public function getValidationAttributes()
    {
        return [
            "{$this->getResourceName()}.client_uuid" => 'client',
            "{$this->getResourceName()}.invoice_uuid" => 'invoice',
            "{$this->getResourceName()}.amount" => 'payment\'s amount',
            "{$this->getResourceName()}.currency_id" => 'currency',
            "{$this->getResourceName()}.payment_type_id" => 'payment\'s type',
            "{$this->getResourceName()}.payment_date" => 'payment\'s date'
        ];
    }
}