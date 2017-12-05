<?php

namespace App\Domain\Model\Documents\Vendor;

use App\Domain\Constants\Permission\Actions;
use App\Domain\Model\Authentication\User\User;

class VendorPolicy
{
    /**
     * Determine if user can view list of vendors.
     *
     * @param  User   $user
     * @return bool
     */
    public function view(User $user)
    {
        return $user->hasPermissionTo(Actions::VIEW, Vendor::class);
    }

    /**
     * Determine if user can see given vendor.
     *
     * @param  User $user
     * @param  Vendor $vendor
     * @return bool
     */
    public function see(User $user, Vendor $vendor)
    {
        return $user->hasPermissionTo(Actions::VIEW, $vendor);
    }

    /**
     * Determine if user can create a vendor.
     *
     * @param  User   $user
     * @return bool
     */
    public function create(User $user)
    {
        return $user->hasPermissionTo(Actions::CREATE, Vendor::class);
    }

    /**
     * Determine if the given vendor can be updated by the user.
     *
     * @param  User   $user
     * @param  Vendor $vendor
     * @return bool
     */
    public function update(User $user, Vendor $vendor)
    {
        return $user->hasPermissionTo(Actions::EDIT, $vendor);
    }

    /**
     * Determine if the given vendor can be deleted by the user.
     *
     * @param  User   $user
     * @param  Vendor $vendor
     * @return bool
     */
    public function delete(User $user, Vendor $vendor)
    {
        return $user->hasPermissionTo(Actions::DELETE, $vendor);
    }

    /**
     * Determine if the given vendor can be archived by the user.
     *
     * @param  User   $user
     * @param  Vendor $vendor
     * @return bool
     */
    public function archive(User $user, Vendor $vendor)
    {
        return $user->hasPermissionTo(Actions::EDIT, $vendor);
    }
}