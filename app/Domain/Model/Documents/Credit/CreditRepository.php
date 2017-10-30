<?php

namespace App\Domain\Model\Documents\Credit;

use App\Domain\Model\Documents\Shared\AbstractDocumentRepository;
use App\Infrastructure\Persistence\Repository;
use App\Domain\Model\Authentication\User\UserRepository;
use App\Domain\Model\Documents\Shared\Traits\FillsUserData;

class CreditRepository extends AbstractDocumentRepository
{
    use FillsUserData;

    protected $repository;
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->repository = new Repository(Credit::class);
        $this->userRepository = $userRepository;
    }

    public function fillDefaultData(&$data, &$protectedData)
    {
        $data['amount'] = $data['balance'];
    }
}