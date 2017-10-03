<?php

namespace App\Domain\Model\Documents\Expense;

use App\Domain\Model\Documents\Shared\AbstractDocumentRepository;
use App\Infrastructure\Persistence\Repository;
use App\Domain\Model\Authentication\User\UserRepository;
use App\Domain\Model\Documents\Shared\Traits\FillsUserData;

class ExpenseRepository extends AbstractDocumentRepository
{
    use FillsUserData;

    protected $repository;
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->repository = new Repository(Expense::class);
        $this->userRepository = $userRepository;
    }

    public function fillMissingData(&$data, &$protectedData)
    {
        if (empty($data['date'])) {
            $data['date'] = date('Y-m-d');
        }
    }
}