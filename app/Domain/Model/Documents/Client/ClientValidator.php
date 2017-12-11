<?php

namespace App\Domain\Model\Documents\Client;

use App\Domain\Model\Shared\Validation\DocumentValidator;
use Illuminate\Validation\ValidationException;

class ClientValidator extends DocumentValidator
{
    public function create()
    {
        $this->validateBase();
        $this->validateName();
        $this->validateCountry();
    }

    public function update()
    {
        $this->validateBase();
        $this->validateName();
        $this->validateCountry();
    }

    /**
     * Check if client object is present
     */
    private function validateBase()
    {
        $this->validate([
            'client' => 'required|array'
        ]);
    }

    /**
     * Check if name is set
     */
    private function validateName()
    {
        $this->validate([
            'client.name' => 'required'
        ], [
            'client.name' => 'client name'
        ]);
    }

    private function validateCountry()
    {
        $this->validate([
            'client.country_id' => 'required|exists:countries,id'
        ], [
            'client.country_id' => 'country'
        ]);

        if (!$this->user->assignAllCountries) {
            $hasCountryAssigned = $this->user->countries()->where('countries.id', $this->request->get('client')['country_id'])->exists();

            if (!$hasCountryAssigned) {
                $validator = $this->getValidationFactory()->make([], []);
                $validator->errors()->add('client.country_id', 'Selected country is not assigned.');

                throw new ValidationException($validator);
            }
        }
    }

    private function validateAdditionData()
    {
        $this->validate([
            'client.currency_code' => 'nullable|exists:currencies,code',
            'client.language_id' => 'nullable|exists:languages,id',
            'client.company_size_id' => 'nullable|exists:company_sizes,id',
            'client.industry_id' => 'nullable|exists:industries,id',
            'client.payment_terms' => 'nullable|numeric'
        ], [
            'client.currency_code' => 'currency',
            'client.language_id' => 'language',
            'client.company_size_id' => 'company size',
            'client.industry_id' => 'industry',
            'client.payment_terms' => 'payment terms'
        ]);
    }
}