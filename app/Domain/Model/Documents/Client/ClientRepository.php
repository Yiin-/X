<?php

namespace App\Domain\Model\Documents\Client;

use App\Domain\Model\Documents\Profile\ProfileRepository;
use App\Domain\Model\Documents\Shared\AbstractDocumentRepository;
use App\Infrastructure\Persistence\Repository;
use App\Domain\Model\Authentication\User\UserRepository;

class ClientRepository extends AbstractDocumentRepository
{
    protected $repository;
    protected $userRepository;
    protected $profileRepository;

    public function __construct(UserRepository $userRepository, ProfileRepository $profileRepository)
    {
        $this->repository = new Repository(Client::class);
        $this->userRepository = $userRepository;
        $this->profileRepository = $profileRepository;
    }

    /**
     * TODO: throw custom exception, if user is not defined
     * @param $data
     * @param array $protectedData
     * @return mixed
     */
    public function create($data, $protectedData = [])
    {
        if (!isset($protectedData['user_uuid'])) {
            $protectedData['user_uuid'] = auth()->id();
        }
        $user = $this->userRepository->find($protectedData['user_uuid']);

        if (!isset($protectedData['company_uuid'])) {
            // TODO: Pick current selected company, not the first one
            $protectedData['company_uuid'] = $user->companies()->first()->uuid;
        }

        $client = $this->repository->create($data, $protectedData);

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

        return $client;
    }
}