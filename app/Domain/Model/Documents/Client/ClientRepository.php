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
    protected $validator;
    protected $contactRepository;

    public function __construct(ClientValidator $clientValidator, ContactRepository $contactRepository)
    {
        $this->setRepository(new Repository(Client::class));
        $this->setValidator($clientValidator);

        $this->contactRepository = $contactRepository;
    }

    public function created(&$client)
    {
        /**
         * Assign created client to the user
         */
        if (!$client->user->assignAllClients) {
            $client->user->clients()->attach($client->uuid);
            $client->user->authenticable->touch();
        }
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