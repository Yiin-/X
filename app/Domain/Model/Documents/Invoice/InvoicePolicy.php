<?php

namespace App\Domain\Model\Documents\Invoice;

use App\Domain\Constants\Permission\Actions;
use App\Domain\Model\Authentication\User\User;

class InvoicePolicy
{
    /**
     * Determine if user can view list of invoices.
     *
     * @param  User   $user
     * @return bool
     */
    public function view(User $user)
    {
        return $user->hasPermissionTo(Actions::VIEW, Invoice::class);
    }

    /**
     * Determine if user can see given invoice.
     *
     * @param  User $user
     * @param  Invoice $invoice
     * @return bool
     */
    public function see(User $user, Invoice $invoice)
    {
        return $user->hasPermissionTo(Actions::VIEW, $invoice);
    }

    /**
     * Determine if user can create a invoice.
     *
     * @param  User   $user
     * @return bool
     */
    public function create(User $user)
    {
        return $user->hasPermissionTo(Actions::CREATE, Invoice::class);
    }

    /**
     * Determine if the given invoice can be updated by the user.
     *
     * @param  User   $user
     * @param  Invoice $invoice
     * @return bool
     */
    public function update(User $user, Invoice $invoice)
    {
        return $user->hasPermissionTo(Actions::EDIT, $invoice);
    }

    /**
     * Determine if the given invoice can be deleted by the user.
     *
     * @param  User   $user
     * @param  Invoice $invoice
     * @return bool
     */
    public function delete(User $user, Invoice $invoice)
    {
        return $user->hasPermissionTo(Actions::DELETE, $invoice);
    }

    /**
     * Determine if the given invoice can be archived by the user.
     *
     * @param  User   $user
     * @param  Invoice $invoice
     * @return bool
     */
    public function archive(User $user, Invoice $invoice)
    {
        return $user->hasPermissionTo(Actions::ARCHIVE, $invoice);
    }
}