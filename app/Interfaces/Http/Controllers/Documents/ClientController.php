<?php

namespace App\Interfaces\Http\Controllers\Documents;

use App\Interfaces\Http\Controllers\DocumentController;
use App\Domain\Model\Documents\Client\ClientRepository;

class ClientController extends DocumentController
{
    protected $repository;

    public function __construct(ClientRepository $clientRepository)
    {
        $this->repository = $clientRepository;
    }

    public function getResourceName()
    {
        return 'client';
    }

    public function getValidationRules($action)
    {
        $rules = [
            static::VALIDATION_RULES_CREATE => [
                $this->getResourceName() => 'required|array',
                "{$this->getResourceName()}.name" => 'required',
                "{$this->getResourceName()}.country_id" => 'nullable|exists:countries,id',
                "{$this->getResourceName()}.currency_code" => 'nullable|exists:currencies,code',
                "{$this->getResourceName()}.language_id" => 'nullable|exists:languages,id',
                "{$this->getResourceName()}.company_size_id" => 'nullable|exists:company_sizes,id',
                "{$this->getResourceName()}.industry_id" => 'nullable|exists:industries,id',
                "{$this->getResourceName()}.payment_terms" => 'nullable|numeric'
            ]
        ];
        $rules[static::VALIDATION_RULES_PATCH] =
            $rules[static::VALIDATION_RULES_UPDATE] =
                $rules[static::VALIDATION_RULES_CREATE];

        return $rules[$action];
    }

    public function getValidationAttributes()
    {
        return [
            "{$this->getResourceName()}.name" => 'client\'s name',
            "{$this->getResourceName()}.country_id" => 'client\'s country',
            "{$this->getResourceName()}.currency_code" => 'client\'s currency',
            "{$this->getResourceName()}.language_id" => 'client\'s language',
            "{$this->getResourceName()}.company_size_id" => 'client\'s company size',
            "{$this->getResourceName()}.industry_id" => 'client\'s industry',
            "{$this->getResourceName()}.payment_terms" => 'payment terms'
        ];
    }
}