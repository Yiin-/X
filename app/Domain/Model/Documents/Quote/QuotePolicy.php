<?php

namespace App\Domain\Model\Documents\Quote;

use App\Domain\Constants\Permission\Actions;
use App\Domain\Model\Authentication\User\User;

class QuotePolicy
{
    /**
     * Determine if user can view list of quotes.
     *
     * @param  User   $user
     * @return bool
     */
    public function view(User $user)
    {
        return $user->hasPermissionTo(Actions::VIEW, Quote::class);
    }

    /**
     * Determine if user can see given quote.
     *
     * @param  User $user
     * @param  Quote $quote
     * @return bool
     */
    public function see(User $user, Quote $quote)
    {
        return $user->hasPermissionTo(Actions::VIEW, $quote);
    }

    /**
     * Determine if user can create a quote.
     *
     * @param  User   $user
     * @return bool
     */
    public function create(User $user)
    {
        return $user->hasPermissionTo(Actions::CREATE, Quote::class);
    }

    /**
     * Determine if the given quote can be updated by the user.
     *
     * @param  User   $user
     * @param  Quote $quote
     * @return bool
     */
    public function update(User $user, Quote $quote)
    {
        return $user->hasPermissionTo(Actions::EDIT, $quote);
    }

    /**
     * Determine if the given quote can be deleted by the user.
     *
     * @param  User   $user
     * @param  Quote $quote
     * @return bool
     */
    public function delete(User $user, Quote $quote)
    {
        return $user->hasPermissionTo(Actions::DELETE, $quote);
    }

    /**
     * Determine if the given quote can be archived by the user.
     *
     * @param  User   $user
     * @param  Quote $quote
     * @return bool
     */
    public function archive(User $user, Quote $quote)
    {
        return $user->hasPermissionTo(Actions::EDIT, $quote);
    }
}