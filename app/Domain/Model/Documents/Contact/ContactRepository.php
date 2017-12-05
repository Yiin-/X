<?php

namespace App\Domain\Model\Documents\Contact;

use App\Domain\Model\Documents\Shared\AbstractDocumentRepository;
use App\Infrastructure\Persistence\Repository;

class ContactRepository extends AbstractDocumentRepository
{
    protected $repository;

    public function __construct()
    {
        $this->repository = new Repository(Contact::class);
    }
}