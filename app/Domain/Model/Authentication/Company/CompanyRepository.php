<?php

namespace App\Domain\Model\Authentication\Company;

use App\Domain\Model\Documents\Shared\AbstractDocumentRepository;
use App\Infrastructure\Persistence\Repository;
use Auth;

class CompanyRepository extends AbstractDocumentRepository
{
    protected $repository;

    protected $auth;

    public function __construct(Auth $auth)
    {
        $this->repository = new Repository(Company::class);
        $this->auth = $auth;
    }

    public function creating(&$data, &$protectedData)
    {
        if (!isset($protectedData['account_uuid'])) {
            $protectedData['account_uuid'] = $this->auth->user()->account_uuid;
        }
    }
}