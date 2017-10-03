<?php

namespace App\Domain\Model\Authentication\Account;

use App\Domain\Model\Documents\Shared\AbstractDocumentRepository;
use App\Infrastructure\Persistence\Repository;

class AccountRepository extends AbstractDocumentRepository
{
    protected $repository;

    public function __construct()
    {
        $this->repository = new Repository(Account::class);
    }

    public function findBySiteAddress($siteAddress)
    {
        return $this->repository->newQuery()->where('site_address', $siteAddress)->first();
    }
}