<?php

namespace App\Domain\Model\CRM\Project;

use League\Fractal;
use App\Domain\Model\CRM\TaskList\TaskListTransformer;
use App\Domain\Model\CRM\Task\TaskTransformer;
use App\Domain\Model\System\ActivityLog\ActivityTransformer;

class ProjectTransformer extends Fractal\TransformerAbstract
{
    protected $defaultIncludes = [
        'task_lists',
        'tasks',
        'history'
    ];

    public function excludeForBackup()
    {
        return ['task_lists', 'tasks', 'history'];
    }

    public function transform(Project $project)
    {
        return [
            'uuid' => $project->uuid,
            'company_uuid' => $project->company_uuid,

            'name' => $project->name,
            'description' => $project->description,

            'client_uuid' => $project->client_uuid,

            'created_at' => $client->created_at,
            'updated_at' => $client->updated_at,
            'archived_at' => $client->archived_at,
            'deleted_at' => $client->deleted_at
        ];
    }

    public function includeTaskLists(Project $project)
    {
        return $this->collection($project->taskLists, new TaskListTransformer);
    }

    public function includeTasks(Project $project)
    {
        return $this->collection($project->tasks, new TaskTransformer);
    }

    public function includeHistory(Project $project)
    {
        return $this->collection($project->getHistory(), new ActivityTransformer);
    }
}