<?php

namespace App\Interfaces\Http\Controllers\CRM;

use App\Interfaces\Http\Controllers\DocumentController;
use App\Domain\Model\CRM\Project\ProjectRepository;
use App\Domain\Model\CRM\Project\Project;
use App\Domain\Model\CRM\TaskList\TaskListRepository;
use App\Domain\Model\CRM\Task\TaskRepository;
use App\Interfaces\Http\Requests\CRM\StoreTaskListRequest;
use App\Interfaces\Http\Requests\CRM\StoreTaskRequest;

class ProjectController extends DocumentController
{
    protected $repository;
    protected $taskListRepository;
    protected $taskRepository;

    public function __construct(
       ProjectRepository $projectRepository,
       TaskListRepository $taskListRepository,
       TaskRepository $taskRepository
    ) {
        $this->repository = $projectRepository;
        $this->taskListRepository = $taskListRepository;
        $this->taskRepository = $taskRepository;
    }

    public function storeTaskList($project, StoreTaskListRequest $request)
    {
        return $this->taskListRepository->create(array_merge([
            'project_uuid' => $project
        ], $request->get('task-list')))->getTableData();
    }

    public function storeTask($project, $taskList, StoreTaskRequest $request)
    {
        return $this->taskRepository->create(array_merge([
            'task_list_uuid' => $taskList
        ], $request->get('task')))->getTableData();
    }

    public function getResourceName()
    {
        return 'project';
    }

    public function getValidationRules($action)
    {
        $rules = [
            static::VALIDATION_RULES_CREATE => [
                $this->getResourceName() => 'required|array',
                "{$this->getResourceName()}.client_uuid" => 'nullable|exists:clients,id',
                "{$this->getResourceName()}.name" => 'required',
            ],
            static::VALIDATION_RULES_PATCH => [
                $this->getResourceName() => 'required|array',
                "{$this->getResourceName()}.client_uuid" => 'nullable|exists:clients,id'
            ]
        ];
        $rules[static::VALIDATION_RULES_UPDATE] = $rules[static::VALIDATION_RULES_CREATE];

        return $rules[$action];
    }

    public function getValidationAttributes()
    {
        return [
            "{$this->getResourceName()}.name" => 'project\'s name',
            "{$this->getResourceName()}.client_uuid" => 'client',
        ];
    }
}