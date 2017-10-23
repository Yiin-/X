<?php

namespace App\Domain\Model\CRM\Project;

use App\Domain\Model\Documents\Shared\DocumentTransformer;
use App\Domain\Model\CRM\TaskList\TaskListTransformer;
use App\Domain\Model\CRM\Task\TaskTransformer;

class ProjectTransformer extends DocumentTransformer
{
    public function map(Project $project)
    {
        return [
            'name' => $project->name,
            'description' => $project->description,

            'client' => [ 'uuid' => $project->client_uuid ],

            'taskLists' => $project->taskLists->map(function ($taskList) {
                return (new TaskListTransformer)->transform($taskList);
            }),
            'tasks' => $project->tasks->map(function ($task) {
                return (new TaskTransformer)->transform($task);
            })
        ];
    }
}