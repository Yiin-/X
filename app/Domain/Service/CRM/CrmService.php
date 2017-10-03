<?php

namespace App\Domain\Service\CRM;

use App\Domain\Model\CRM\Project\ProjectRepository;

class CrmService
{
    public function __construct(
        ProjectRepository $projectRepository
    ) {
        $this->projectRepository = $projectRepository;
    }

    public function getAll($user = null)
    {
        return [
            'projects' => $this->projectRepository->getVisible($user ? $user->uuid : auth()->id())
        ];
    }
}