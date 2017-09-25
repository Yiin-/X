<?php

namespace App\Domain\Model\Documents\TaxRate;

use App\Domain\Constants\Permission\Actions;
use App\Domain\Model\Authentication\User\User;

class TaxRatePolicy
{
    /**
     * Determine if user can view list of tax rates.
     *
     * @param  User   $user
     * @return bool
     */
    public function view(User $user)
    {
        return $user->hasPermissionTo(Actions::VIEW, TaxRate::class);
    }

    /**
     * Determine if user can see given tax rate.
     *
     * @param  User $user
     * @param  TaxRate $taxRate
     * @return bool
     */
    public function see(User $user, TaxRate $taxRate)
    {
        return $user->hasPermissionTo(Actions::VIEW, $taxRate);
    }

    /**
     * Determine if user can create a tax rate.
     *
     * @param  User   $user
     * @return bool
     */
    public function create(User $user)
    {
        return $user->hasPermissionTo(Actions::CREATE, TaxRate::class);
    }

    /**
     * Determine if the given tax rate can be updated by the user.
     *
     * @param  User   $user
     * @param  TaxRate $taxRate
     * @return bool
     */
    public function update(User $user, TaxRate $taxRate)
    {
        return $user->hasPermissionTo(Actions::EDIT, $taxRate);
    }

    /**
     * Determine if the given tax rate can be deleted by the user.
     *
     * @param  User   $user
     * @param  TaxRate $taxRate
     * @return bool
     */
    public function delete(User $user, TaxRate $taxRate)
    {
        return $user->hasPermissionTo(Actions::DELETE, $taxRate);
    }

    /**
     * Determine if the given tax rate can be archived by the user.
     *
     * @param  User   $user
     * @param  TaxRate $taxRate
     * @return bool
     */
    public function archive(User $user, TaxRate $taxRate)
    {
        return $user->hasPermissionTo(Actions::ARCHIVE, $taxRate);
    }
}