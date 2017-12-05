<?php

namespace App\Domain\Model\CRM\Project;

use App\Domain\Constants\Permission\Actions;
use App\Domain\Model\Authentication\User\User;

class ProjectPolicy
{
    /**
     * Determine if user can view list of projects.
     *
     * @param  User   $user
     * @return bool
     */
    public function view(User $user)
    {
        return $user->hasPermissionTo(Actions::VIEW, Project::class);
    }

    /**
     * Determine if user can see given project.
     *
     * @param  User $user
     * @param  Project $project
     * @return bool
     */
    public function see(User $user, Project $project)
    {
        return $user->hasPermissionTo(Actions::VIEW, $project);
    }

    /**
     * Determine if user can create a project.
     *
     * @param  User   $user
     * @return bool
     */
    public function create(User $user)
    {
        return $user->hasPermissionTo(Actions::CREATE, Project::class);
    }

    /**
     * Determine if the given project can be updated by the user.
     *
     * @param  User   $user
     * @param  Project $project
     * @return bool
     */
    public function update(User $user, Project $project)
    {
        return $user->hasPermissionTo(Actions::EDIT, $project);
    }

    /**
     * Determine if the given project can be deleted by the user.
     *
     * @param  User   $user
     * @param  Project $project
     * @return bool
     */
    public function delete(User $user, Project $project)
    {
        return $user->hasPermissionTo(Actions::DELETE, $project);
    }

    /**
     * Determine if the given project can be archived by the user.
     *
     * @param  User   $user
     * @param  Project $project
     * @return bool
     */
    public function archive(User $user, Project $project)
    {
        return $user->hasPermissionTo(Actions::EDIT, $project);
    }
}