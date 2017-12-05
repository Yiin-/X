<?php

namespace App\Domain\Model\Documents\Vendor;

use App\Domain\Model\Documents\Shared\AbstractDocumentRepository;
use App\Infrastructure\Persistence\Repository;
use App\Domain\Model\Documents\Contact\Contact;
use App\Domain\Model\Documents\Contact\ContactRepository;
use App\Domain\Model\Documents\Shared\Traits\FillsUserData;

class VendorRepository extends AbstractDocumentRepository
{
    use FillsUserData;

    protected $repository;
    protected $contactRepository;

    public function __construct(ContactRepository $contactRepository)
    {
        $this->repository = new Repository(Vendor::class);
        $this->contactRepository = $contactRepository;
    }

    public function saved(&$vendor, &$data)
    {
        foreach ($data['contacts'] as $contact) {
            if ($contact['uuid']) {
                $vendor->contacts()->find($contact['uuid'])->update($contact);
            } else {
                $contactModel = new Contact;
                $contactModel->uuid = $this->contactRepository->generateUuid();
                $contactModel->fill($contact);

                $vendor->contacts()->save($contactModel);
            }
        }

        if (!$vendor->isDirty()) {
            $vendor->touch();
        }

        $vendor->load('contacts');
    }
}