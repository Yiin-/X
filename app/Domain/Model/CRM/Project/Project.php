<?php

namespace App\Domain\Model\CRM\Project;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Domain\Model\Documents\Shared\AbstractDocument;
use App\Domain\Model\Authentication\User\User;
use App\Domain\Model\Authentication\Company\Company;
use App\Domain\Model\CRM\TaskList\TaskList;
use App\Domain\Model\CRM\Task\Task;

class Project extends AbstractDocument
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'client_uuid'
    ];

    protected $hidden = [
        'id',
        'company_uuid',
        'user_uuid'
    ];

    public function getTransformer()
    {
        return new ProjectTransformer;
    }

    public function taskLists()
    {
        return $this->hasMany(TaskList::class);
    }

    public function tasks()
    {
        return $this->hasManyThrough(Task::class, TaskList::class);
    }
}