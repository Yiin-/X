<?php

namespace App\Domain\Model\Authentication\Account;

use App\Domain\Model\Documents\Shared\AbstractDocumentRepository;
use App\Infrastructure\Persistence\Repository;
use Auth;

class AccountRepository extends AbstractDocumentRepository
{
    protected $repository;

    protected $auth;

    public function __construct(Auth $auth)
    {
        $this->repository = new Repository(Account::class);
        $this->auth = $auth;
    }
}