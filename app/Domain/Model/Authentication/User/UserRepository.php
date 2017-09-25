<?php

namespace App\Domain\Model\Authentication\User;

use App\Domain\Model\Documents\Shared\AbstractDocumentRepository;
use App\Infrastructure\Persistence\Repository;

class UserRepository extends AbstractDocumentRepository
{
    protected $repository;

    public function __construct()
    {
        $this->repository = new Repository(User::class);
    }

    public function create(array $data, $protectedData = [])
    {
        $password = password_hash($data['password'], PASSWORD_BCRYPT);

        return $this->repository->create($data, array_merge([
            'password' => $password
        ], $protectedData));
    }

    public function findByUsername($siteAddress, $username)
    {
        return $this->repository->newQuery()
                    ->whereHas('account', function ($query) use ($siteAddress) {
                        $query->where('site_address', $siteAddress);
                    })
                    ->where('username', $username)
                    ->first();
    }
}