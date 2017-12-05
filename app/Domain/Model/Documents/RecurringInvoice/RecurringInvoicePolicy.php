<?php

namespace App\Domain\Model\Documents\RecurringInvoice;

use App\Domain\Constants\Permission\Actions;
use App\Domain\Model\Authentication\User\User;

class RecurringInvoicePolicy
{
    /**
     * Determine if user can view list of recurring invoices.
     *
     * @param  User   $user
     * @return bool
     */
    public function view(User $user)
    {
        return $user->hasPermissionTo(Actions::VIEW, RecurringInvoice::class);
    }

    /**
     * Determine if user can see given recurring invoice.
     *
     * @param  User $user
     * @param  RecurringInvoice $recurringInvoice
     * @return bool
     */
    public function see(User $user, RecurringInvoice $recurringInvoice)
    {
        return $user->hasPermissionTo(Actions::VIEW, $recurringInvoice);
    }

    /**
     * Determine if user can create a recurring invoice.
     *
     * @param  User   $user
     * @return bool
     */
    public function create(User $user)
    {
        return $user->hasPermissionTo(Actions::CREATE, RecurringInvoice::class);
    }

    /**
     * Determine if the given recurring invoice can be updated by the user.
     *
     * @param  User   $user
     * @param  RecurringInvoice $recurringInvoice
     * @return bool
     */
    public function update(User $user, RecurringInvoice $recurringInvoice)
    {
        return $user->hasPermissionTo(Actions::EDIT, $recurringInvoice);
    }

    /**
     * Determine if the given recurring invoice can be deleted by the user.
     *
     * @param  User   $user
     * @param  RecurringInvoice $recurringInvoice
     * @return bool
     */
    public function delete(User $user, RecurringInvoice $recurringInvoice)
    {
        return $user->hasPermissionTo(Actions::DELETE, $recurringInvoice);
    }

    /**
     * Determine if the given recurring invoice can be archived by the user.
     *
     * @param  User   $user
     * @param  RecurringInvoice $recurringInvoice
     * @return bool
     */
    public function archive(User $user, RecurringInvoice $recurringInvoice)
    {
        return $user->hasPermissionTo(Actions::EDIT, $recurringInvoice);
    }
}