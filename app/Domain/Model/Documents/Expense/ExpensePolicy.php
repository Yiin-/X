<?php

namespace App\Domain\Model\Documents\Expense;

use App\Domain\Constants\Permission\Actions;
use App\Domain\Model\Authentication\User\User;

class ExpensePolicy
{
    /**
     * Determine if user can view list of expenses.
     *
     * @param  User   $user
     * @return bool
     */
    public function view(User $user)
    {
        return $user->hasPermissionTo(Actions::VIEW, Expense::class);
    }

    /**
     * Determine if user can see given expense.
     *
     * @param  User $user
     * @param  Expense $expense
     * @return bool
     */
    public function see(User $user, Expense $expense)
    {
        return $user->hasPermissionTo(Actions::VIEW, $expense);
    }

    /**
     * Determine if user can create a expense.
     *
     * @param  User   $user
     * @return bool
     */
    public function create(User $user)
    {
        return $user->hasPermissionTo(Actions::CREATE, Expense::class);
    }

    /**
     * Determine if the given expense can be updated by the user.
     *
     * @param  User   $user
     * @param  Expense $expense
     * @return bool
     */
    public function update(User $user, Expense $expense)
    {
        return $user->hasPermissionTo(Actions::EDIT, $expense);
    }

    /**
     * Determine if the given expense can be deleted by the user.
     *
     * @param  User   $user
     * @param  Expense $expense
     * @return bool
     */
    public function delete(User $user, Expense $expense)
    {
        return $user->hasPermissionTo(Actions::DELETE, $expense);
    }

    /**
     * Determine if the given expense can be archived by the user.
     *
     * @param  User   $user
     * @param  Expense $expense
     * @return bool
     */
    public function archive(User $user, Expense $expense)
    {
        return $user->hasPermissionTo(Actions::EDIT, $expense);
    }
}