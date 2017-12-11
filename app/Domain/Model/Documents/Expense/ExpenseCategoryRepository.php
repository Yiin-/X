<?php

namespace App\Domain\Model\Documents\Expense;

use App\Domain\Model\Documents\Shared\AbstractDocumentRepository;
use App\Infrastructure\Persistence\Repository;
use App\Domain\Model\Authentication\User\UserRepository;

class ExpenseCategoryRepository extends AbstractDocumentRepository
{
    protected $repository;
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->repository = new Repository(ExpenseCategory::class);
        $this->userRepository = $userRepository;
    }
}