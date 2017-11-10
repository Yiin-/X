<?php

namespace App\Domain\Model\CRM\Task;

use League\Fractal;
use App\Domain\Model\Authentication\User\UserTransformer;
use App\Domain\Model\System\ActivityLog\ActivityTransformer;

class TaskTransformer extends Fractal\TransformerAbstract
{
    protected $defaultIncludes = [
        'user',
        'history'
    ];

    public function transform(Task $task)
    {
        return [
            'name' => $task->name,
            'is_completed' => $task->is_completed,

            'is_disabled' => false
        ];
    }

    public function includeUser(Task $task)
    {
        return $this->item($task->user, new UserTransformer);
    }

    public function includeHistory(Task $task)
    {
        return $this->collection($task->getHistory(), new ActivityTransformer);
    }
}