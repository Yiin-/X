<?php

namespace App\Interfaces\Http\Controllers\Documents;

use App\Interfaces\Http\Controllers\DocumentController;
use App\Domain\Model\Documents\Quote\QuoteRepository;

class QuoteController extends DocumentController
{
    protected $repository;

    public function __construct(QuoteRepository $quoteRepository)
    {
        $this->repository = $quoteRepository;
    }

    public function getResourceName()
    {
        return 'quote';
    }

    public function getValidationRules($action)
    {
        $rules = [
            static::VALIDATION_RULES_CREATE => [
                $this->getResourceName() => 'required|array',
                "{$this->getResourceName()}.client_uuid" => 'required|exists:clients,uuid',
                "{$this->getResourceName()}.quote_number" => 'nullable|required',
                "{$this->getResourceName()}.quote_date" => 'nullable|date',
                "{$this->getResourceName()}.due_date" => 'nullable|date',
                "{$this->getResourceName()}.partial" => 'nullable|numeric',
                "{$this->getResourceName()}.items" => "array"
                // "{$this->getResourceName()}.discount.type" => '',
            ],
            static::VALIDATION_RULES_PATCH => [
                $this->getResourceName() => 'required|array',
                "{$this->getResourceName()}.client_uuid" => 'exists:clients,uuid',
                "{$this->getResourceName()}.quote_date" => 'nullable|date',
                "{$this->getResourceName()}.due_date" => 'nullable|date',
                "{$this->getResourceName()}.partial" => 'nullable|numeric',
                "{$this->getResourceName()}.items" => "array"
            ]
        ];
        $rules[static::VALIDATION_RULES_UPDATE] = $rules[static::VALIDATION_RULES_CREATE];

        return $rules[$action];
    }

    public function getValidationAttributes()
    {
        return [
            "{$this->getResourceName()}.client_uuid" => 'client',
            "{$this->getResourceName()}.quote_number" => 'quote number',
            "{$this->getResourceName()}.quote_date" => 'quote\'s date',
            "{$this->getResourceName()}.due_date" => 'due date',
            "{$this->getResourceName()}.partial" => 'partial'
        ];
    }
}