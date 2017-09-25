<?php

namespace App\Domain\Model\Documents\Credit;

use App\Domain\Constants\Permission\Actions;
use App\Domain\Model\Authentication\User\User;

class CreditPolicy
{
    /**
     * Determine if user can view list of credits.
     *
     * @param  User   $user
     * @return bool
     */
    public function view(User $user)
    {
        return $user->hasPermissionTo(Actions::VIEW, Credit::class);
    }

    /**
     * Determine if user can see given credit.
     *
     * @param  User $user
     * @param  Credit $credit
     * @return bool
     */
    public function see(User $user, Credit $credit)
    {
        return $user->hasPermissionTo(Actions::VIEW, $credit);
    }

    /**
     * Determine if user can create a credit.
     *
     * @param  User   $user
     * @return bool
     */
    public function create(User $user)
    {
        return $user->hasPermissionTo(Actions::CREATE, Credit::class);
    }

    /**
     * Determine if the given credit can be updated by the user.
     *
     * @param  User   $user
     * @param  Credit $credit
     * @return bool
     */
    public function update(User $user, Credit $credit)
    {
        return $user->hasPermissionTo(Actions::EDIT, $credit);
    }

    /**
     * Determine if the given credit can be deleted by the user.
     *
     * @param  User   $user
     * @param  Credit $credit
     * @return bool
     */
    public function delete(User $user, Credit $credit)
    {
        return $user->hasPermissionTo(Actions::DELETE, $credit);
    }

    /**
     * Determine if the given credit can be archived by the user.
     *
     * @param  User   $user
     * @param  Credit $credit
     * @return bool
     */
    public function archive(User $user, Credit $credit)
    {
        return $user->hasPermissionTo(Actions::ARCHIVE, $credit);
    }
}