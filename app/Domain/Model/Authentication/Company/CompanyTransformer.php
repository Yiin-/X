<?php

namespace App\Domain\Model\Authentication\Company;

use League\Fractal;

class CompanyTransformer extends Fractal\TransformerAbstract
{
    public function transform(Company $company)
    {
        return [
            'uuid' => $company->uuid,

            'name' => $company->name,
            'email' => $company->email,
            'logo_url' => $company->logo_url
        ];
    }
}