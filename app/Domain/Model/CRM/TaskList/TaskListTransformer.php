<?php

namespace App\Domain\Model\CRM\TaskList;

use League\Fractal;
use App\Domain\Model\Authentication\User\UserTransformer;
use App\Domain\Model\System\ActivityLog\ActivityTransformer;

class TaskListTransformer extends Fractal\TransformerAbstract
{
    protected $defaultIncludes = [
        'user',
        'tasks',
        'history'
    ];

    public function excludeForBackup()
    {
        return ['user', 'tasks', 'history'];
    }

    public function transform(TaskList $taskList)
    {
        return [
            'uuid' -> $taskList->uuid,
            'company_uuid' -> $taskList->company_uuid,

            'name' => $taskList->name,
            'color' => $taskList->color,

            'is_disabled' => false
        ];
    }

    public function includeUser(Task $task)
    {
        return $this->item($task->user, new UserTransformer);
    }

    public function includeTasks(TaskList $taskList)
    {
        return $this->collection($taskList->items, new TaskTransformer);
    }

    public function includeHistory(Task $task)
    {
        return $this->collection($task->getHistory(), new ActivityTransformer);
    }
}