<?php

namespace App\Domain\Model\Documents\Client;

use App\Domain\Model\Documents\Shared\AbstractDocumentRepository;
use App\Infrastructure\Persistence\Repository;
use App\Domain\Model\Documents\Contact\Contact;
use App\Domain\Model\Documents\Contact\ContactRepository;
use App\Domain\Model\Documents\Shared\Traits\FillsUserData;

class ClientRepository extends AbstractDocumentRepository
{
    use FillsUserData;

    protected $repository;
    protected $contactRepository;

    public function __construct(ContactRepository $contactRepository)
    {
        $this->repository = new Repository(Client::class);
        $this->contactRepository = $contactRepository;
    }

    public function saved(&$client, &$data)
    {
        foreach ($data['contacts'] as $contact) {
            if ($contact['uuid']) {
                $client->contacts()->find($contact['uuid'])->update($contact);
            } else {
                $contactModel = new Contact;
                $contactModel->uuid = $this->contactRepository->generateUuid();
                $contactModel->fill($contact);

                $client->contacts()->save($contactModel);
            }
        }

        if (!$client->isDirty()) {
            $client->touch();
        }

        $client->load('contacts');
    }
}