<?php

namespace App\Domain\Model\CRM\Task;

use App\Domain\Model\Documents\Shared\DocumentTransformer;

class TaskTransformer extends DocumentTransformer
{
    public function map(Task $task)
    {
        return [
            'name' => $task->name,
            'is_completed' => $task->is_completed,

            'user' => $task->user->getTableData()
        ];
    }
}