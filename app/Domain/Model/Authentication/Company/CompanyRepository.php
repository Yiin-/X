<?php

namespace App\Domain\Model\Authentication\Company;

use App\Domain\Model\Documents\Shared\AbstractDocumentRepository;
use App\Infrastructure\Persistence\Repository;
use Auth;

class CompanyRepository extends AbstractDocumentRepository
{
    protected $repository;

    public function __construct()
    {
        $this->repository = new Repository(Company::class);
    }

    public function creating(&$data, &$protectedData)
    {
        if (!isset($protectedData['account_uuid']) && auth()->check()) {
            $protectedData['account_uuid'] = auth()->user()->account_uuid;
        }
    }

    public function created($company)
    {
        if (auth()->check()) {
            auth()->user()->companies()->attach($company->uuid);
            auth()->user()->touch();
        }
    }
}