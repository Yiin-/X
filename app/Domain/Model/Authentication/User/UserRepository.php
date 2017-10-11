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

    public function adjustData(&$data, &$protectedData)
    {
        $password = password_hash($data['password'], PASSWORD_BCRYPT);

        $protectedData = array_merge([
            'password' => $password
        ], $protectedData);
    }

    public function findByUsername($siteAddress, $username)
    {
        return $this->repository->newQuery()
                    ->when($username === 'demo', function ($query) {
                        return $query->whereHas('account', function ($query) use ($siteAddress) {
                            $query->where('site_address', $siteAddress);
                        });
                    })
                    ->where('username', $username)
                    ->first();
    }
}