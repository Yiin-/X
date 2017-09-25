<?php

namespace App\Domain\Model\CRM\TaskList;

use App\Domain\Constants\Permission\Actions;
use App\Domain\Model\Authentication\User\User;

class TaskListPolicy
{
    /**
     * Determine if user can view list of task lists.
     *
     * @param  User   $user
     * @return bool
     */
    public function view(User $user)
    {
        return $user->hasPermissionTo(Actions::VIEW, TaskList::class);
    }

    /**
     * Determine if user can see given task list.
     *
     * @param  User $user
     * @param  TaskList $taskList
     * @return bool
     */
    public function see(User $user, TaskList $taskList)
    {
        return $user->hasPermissionTo(Actions::VIEW, $taskList);
    }

    /**
     * Determine if user can create a task list.
     *
     * @param  User   $user
     * @return bool
     */
    public function create(User $user)
    {
        return $user->hasPermissionTo(Actions::CREATE, TaskList::class);
    }

    /**
     * Determine if the given task list can be updated by the user.
     *
     * @param  User   $user
     * @param  TaskList $taskList
     * @return bool
     */
    public function update(User $user, TaskList $taskList)
    {
        return $user->hasPermissionTo(Actions::EDIT, $taskList);
    }

    /**
     * Determine if the given task list can be deleted by the user.
     *
     * @param  User   $user
     * @param  TaskList $taskList
     * @return bool
     */
    public function delete(User $user, TaskList $taskList)
    {
        return $user->hasPermissionTo(Actions::DELETE, $taskList);
    }

    /**
     * Determine if the given task list can be archived by the user.
     *
     * @param  User   $user
     * @param  TaskList $taskList
     * @return bool
     */
    public function archive(User $user, TaskList $taskList)
    {
        return $user->hasPermissionTo(Actions::ARCHIVE, $taskList);
    }
}