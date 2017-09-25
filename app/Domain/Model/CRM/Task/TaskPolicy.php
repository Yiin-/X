<?php

namespace App\Domain\Model\CRM\Task;

use App\Domain\Constants\Permission\Actions;
use App\Domain\Model\Authentication\User\User;

class TaskPolicy
{
    /**
     * Determine if user can view list of tasks.
     *
     * @param  User   $user
     * @return bool
     */
    public function view(User $user)
    {
        return $user->hasPermissionTo(Actions::VIEW, Task::class);
    }

    /**
     * Determine if user can see given task.
     *
     * @param  User $user
     * @param  Task $task
     * @return bool
     */
    public function see(User $user, Task $task)
    {
        return $user->hasPermissionTo(Actions::VIEW, $task);
    }

    /**
     * Determine if user can create a task.
     *
     * @param  User   $user
     * @return bool
     */
    public function create(User $user)
    {
        return $user->hasPermissionTo(Actions::CREATE, Task::class);
    }

    /**
     * Determine if the given task can be updated by the user.
     *
     * @param  User   $user
     * @param  Task $task
     * @return bool
     */
    public function update(User $user, Task $task)
    {
        return $user->hasPermissionTo(Actions::EDIT, $task);
    }

    /**
     * Determine if the given task can be deleted by the user.
     *
     * @param  User   $user
     * @param  Task $task
     * @return bool
     */
    public function delete(User $user, Task $task)
    {
        return $user->hasPermissionTo(Actions::DELETE, $task);
    }

    /**
     * Determine if the given task can be archived by the user.
     *
     * @param  User   $user
     * @param  Task $task
     * @return bool
     */
    public function archive(User $user, Task $task)
    {
        return $user->hasPermissionTo(Actions::ARCHIVE, $task);
    }
}