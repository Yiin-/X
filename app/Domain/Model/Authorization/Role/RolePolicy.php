<?php

namespace App\Domain\Model\Authorization\Role;

use App\Domain\Constants\Permission\Actions;
use App\Domain\Model\Authentication\User\User;

class RolePolicy
{
    /**
     * Determine if user can view list of roles.
     *
     * @param  User   $user
     * @return bool
     */
    public function view(User $user)
    {
        return $user->hasPermissionTo(Actions::VIEW, Role::class);
    }

    /**
     * Determine if user can see given role.
     *
     * @param  User $user
     * @param  Role $role
     * @return bool
     */
    public function see(User $user, Role $role)
    {
        return $user->hasPermissionTo(Actions::VIEW, $role);
    }

    /**
     * Determine if user can create a role.
     *
     * @param  User   $user
     * @return bool
     */
    public function create(User $user)
    {
        return $user->hasPermissionTo(Actions::CREATE, Role::class);
    }

    /**
     * Determine if the given role can be updated by the user.
     *
     * @param  User   $user
     * @param  Role $role
     * @return bool
     */
    public function update(User $user, Role $role)
    {
        return $user->hasPermissionTo(Actions::EDIT, $role);
    }

    /**
     * Determine if the given role can be deleted by the user.
     *
     * @param  User   $user
     * @param  Role $role
     * @return bool
     */
    public function delete(User $user, Role $role)
    {
        return $user->hasPermissionTo(Actions::DELETE, $role);
    }

    /**
     * Determine if the given role can be archived by the user.
     *
     * @param  User   $user
     * @param  Role $role
     * @return bool
     */
    public function archive(User $user, Role $role)
    {
        return $user->hasPermissionTo(Actions::EDIT, $role);
    }
}