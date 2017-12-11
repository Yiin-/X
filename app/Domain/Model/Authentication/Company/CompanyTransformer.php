<?php

namespace App\Domain\Model\Authentication\Company;

use League\Fractal;
use App\Domain\Model\Authorization\Role\RoleTransformer;
use App\Domain\Model\Documents\Employee\Employee;
use App\Domain\Model\Documents\Employee\EmployeeTransformer;

class CompanyTransformer extends Fractal\TransformerAbstract
{
    protected $defaultIncludes = [
        'roles',
        'employees'
    ];

    public function transform(Company $company)
    {
        return [
            'uuid' => $company->uuid,

            'name' => $company->name,
            'email' => $company->email,
            'logo_url' => $company->logo_url,

            'created_at' => $company->created_at,
            'updated_at' => $company->updated_at,
            'deleted_at' => $company->created_at
        ];
    }

    public function includeRoles(Company $company)
    {
        $company->load(['roles']);
        return $this->collection($company->roles, new RoleTransformer);
    }

    public function includeEmployees(Company $company)
    {
        $employees = Employee::with('auth')
            ->where('company_uuid', $company->uuid)
            ->orWhereHas('auth', function ($query) use ($company) {
                return $query->whereIn('uuid', $company->users()->get(['uuid'])->pluck('uuid'));
            })
            ->get();

        return $this->collection($employees, new EmployeeTransformer);
    }
}