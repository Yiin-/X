<?php

namespace App\Domain\Model\Documents\Product;

use App\Domain\Model\Documents\Shared\AbstractDocumentRepository;
use App\Infrastructure\Persistence\Repository;
use App\Domain\Model\Authentication\User\UserRepository;
use App\Domain\Model\Documents\Shared\Traits\FillsUserData;

class ProductRepository extends AbstractDocumentRepository
{
    use FillsUserData;

    protected $repository;
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->repository = new Repository(Product::class);
        $this->userRepository = $userRepository;
    }

    public function adjustData(&$data)
    {
        if (isset($data['is_service']) && $data['is_service']) {
            $data['qty'] = null;
        } else {
            $data['qty'] = !$data['qty'] ? 0 : $data['qty'];
        }
    }
}