<?php

namespace App\Domain\Model\Documents\Payment;

use App\Domain\Constants\Permission\Actions;
use App\Domain\Model\Authentication\User\User;

class PaymentPolicy
{
    /**
     * Determine if user can view list of payments.
     *
     * @param  User   $user
     * @return bool
     */
    public function view(User $user)
    {
        return $user->hasPermissionTo(Actions::VIEW, Payment::class);
    }

    /**
     * Determine if user can see given payment.
     *
     * @param  User $user
     * @param  Payment $payment
     * @return bool
     */
    public function see(User $user, Payment $payment)
    {
        return $user->hasPermissionTo(Actions::VIEW, $payment);
    }

    /**
     * Determine if user can create a payment.
     *
     * @param  User   $user
     * @return bool
     */
    public function create(User $user)
    {
        return $user->hasPermissionTo(Actions::CREATE, Payment::class);
    }

    /**
     * Determine if the given payment can be updated by the user.
     *
     * @param  User   $user
     * @param  Payment $payment
     * @return bool
     */
    public function update(User $user, Payment $payment)
    {
        return $user->hasPermissionTo(Actions::EDIT, $payment);
    }

    /**
     * Determine if the given payment can be deleted by the user.
     *
     * @param  User   $user
     * @param  Payment $payment
     * @return bool
     */
    public function delete(User $user, Payment $payment)
    {
        return $user->hasPermissionTo(Actions::DELETE, $payment);
    }

    /**
     * Determine if the given payment can be archived by the user.
     *
     * @param  User   $user
     * @param  Payment $payment
     * @return bool
     */
    public function archive(User $user, Payment $payment)
    {
        return $user->hasPermissionTo(Actions::EDIT, $payment);
    }
}