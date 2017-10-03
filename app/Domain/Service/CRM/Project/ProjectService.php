<?php

namespace App\Domain\Service\CRM\Project;

use App\Domain\Model\CRM\Project\ProjectRepository;
use App\Domain\Model\CRM\TaskList\TaskListRepository;
use App\Domain\Model\CRM\Task\TaskRepository;

class ProjectService
{
    private $projectRepository;
    private $taskListRepository;
    private $taskRepository;

    public function __construct(
        ProjectRepository $projectRepository,
        TaskListRepository $taskListRepository,
        TaskRepository $taskRepository
    ) {
        $this->projectRepository = $projectRepository;
        $this->taskListRepository = $taskListRepository;
        $this->taskRepository = $taskRepository;
    }

    public function createProject($data)
    {
        $project = $this->projectRepository->create($data);

        return $project->transform();
    }
}