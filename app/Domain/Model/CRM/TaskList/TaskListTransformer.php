<?php

namespace App\Domain\Model\CRM\TaskList;

use App\Domain\Model\Documents\Shared\DocumentTransformer;
use App\Domain\Model\CRM\Task\TaskTransformer;

class TaskListTransformer extends DocumentTransformer
{
    public function map(TaskList $taskList)
    {
        return [
            'name' => $taskList->name,
            'color' => $taskList->color,

            'tasks' => $taskList->tasks->map(function ($task) {
                return (new TaskTransformer)->transform($task);
            }),

            'is_disabled' => false
        ];
    }
}