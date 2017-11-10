<?php

namespace App\Domain\Model\Documents\Client;

use App\Domain\Model\Documents\Profile\ProfileRepository;
use App\Domain\Model\Documents\Shared\AbstractDocumentRepository;
use App\Infrastructure\Persistence\Repository;
use App\Domain\Model\Authentication\User\UserRepository;
use App\Domain\Model\Documents\Shared\Traits\FillsUserData;

class ClientRepository extends AbstractDocumentRepository
{
    use FillsUserData;

    protected $repository;
    protected $userRepository;
    protected $profileRepository;

    public function __construct(UserRepository $userRepository, ProfileRepository $profileRepository)
    {
        $this->repository = new Repository(Client::class);
        $this->userRepository = $userRepository;
        $this->profileRepository = $profileRepository;
    }

    public function created(&$client, &$data, &$protectedData)
    {
        $this->updateContacts($client, $data);
    }

    public function saving(&$client, &$data)
    {
        if (!$client->exists) {
            return;
        }
        $this->updateContacts($client, $data);
    }

    public function updateContacts(&$client, &$data)
    {
        $client->contacts()->delete();

        $profiles = array_map(function ($contact) {
            return $this->profileRepository->create($contact);
        }, $data['contacts']);

        foreach ($profiles as $profile) {
            $client->contacts()->forceCreate([
                'uuid' => $this->repository->generateUuid(),
                'client_uuid' => $client->uuid,
                'profile_uuid' => $profile->uuid
            ]);
        }

        if (!$client->isDirty()) {
            $client->touch();
        }

        $client->load(['contacts', 'contacts.profile']);
    }
}