<?php

namespace App\Domain\Model\Shared\Validation;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Contracts\Validation\Factory;

class DocumentValidator
{
    use ValidatesRequests {
        validate as validateRequest;
    }

    protected $user;
    protected $company;

    public function __construct()
    {
        $this->user = auth()->user();
        $this->company = current_company();
        $this->request = request();
    }

    public function validate($rules, array $messages = [], array $customAttributes = [])
    {
        return $this->validateRequest($this->request, $rules, $messages, $customAttributes);
    }

    protected function getValidationFactory()
    {
        return app(Factory::class);
    }
}