<?php

namespace App\Domain\Model\CRM\Task;

use App\Domain\Model\Documents\Shared\AbstractDocument;

use App\Domain\Model\CRM\TaskList\TaskList;
use App\Domain\Model\Authentication\User\User;

class Task extends AbstractDocument
{
    protected $fillable = [
        'task_list_uuid',
        'name',
        'is_completed'
    ];

    protected $touches = [
        'taskList'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transform()
    {
        return (new TaskTransformer)->transform($this);
    }

    public function taskList()
    {
        return $this->belongsTo(TaskList::class);
    }
}