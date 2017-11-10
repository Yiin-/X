<?php

namespace App\Domain\Model\CRM\TaskList;

use App\Domain\Model\Documents\Shared\AbstractDocument;

use App\Domain\Model\CRM\Project\Project;
use App\Domain\Model\CRM\Task\Task;

class TaskList extends AbstractDocument
{
    protected $fillable = [
        'project_uuid',
        'name',
        'color'
    ];

    protected $hidden = [
        'id',
        'user_uuid'
    ];

    public function getTransformer()
    {
        return new TaskListTransformer;
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}