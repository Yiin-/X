<?php

namespace App\Domain\Model\Documents\Profile;

use App\Domain\Model\Documents\Shared\AbstractDocumentRepository;
use App\Infrastructure\Persistence\Repository;

class ProfileRepository extends AbstractDocumentRepository
{
    protected $repository;

    public function __construct()
    {
        $this->repository = new Repository(Profile::class);
    }
}