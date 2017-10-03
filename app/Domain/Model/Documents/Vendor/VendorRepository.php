<?php

namespace App\Domain\Model\Documents\Vendor;

use App\Domain\Model\Documents\Profile\ProfileRepository;
use App\Domain\Model\Documents\Shared\AbstractDocumentRepository;
use App\Infrastructure\Persistence\Repository;
use App\Domain\Model\Authentication\User\UserRepository;
use App\Domain\Model\Documents\Shared\Traits\FillsUserData;

class VendorRepository extends AbstractDocumentRepository
{
    use FillsUserData;

    protected $repository;
    protected $userRepository;
    protected $profileRepository;

    public function __construct(UserRepository $userRepository, ProfileRepository $profileRepository)
    {
        $this->repository = new Repository(Vendor::class);
        $this->userRepository = $userRepository;
        $this->profileRepository = $profileRepository;
    }

    public function saved(&$vendor, &$data)
    {
        $vendor->contacts()->delete();

        $profiles = array_map(function ($contact) {
            return $this->profileRepository->create($contact);
        }, $data['contacts']);

        foreach ($profiles as $profile) {
            $vendor->contacts()->forceCreate([
                'uuid' => $this->repository->generateUuid(),
                'vendor_uuid' => $vendor->uuid,
                'profile_uuid' => $profile->uuid
            ]);
        }

        return $vendor;
    }
}